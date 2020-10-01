<template>
  <v-sheet :color="colorOfTheDay" :dark="!!colorOfTheDay" class="fill-height transition-background-color">
    <v-container class="full-height">
      <v-expand-transition>
        <v-stepper v-if="step !== '3'" :value="step" alt-labels class="elevation-0 transparent">
          <v-stepper-header class="elevation-0">
            <v-stepper-step step="1" />
            <v-divider></v-divider>
            <v-stepper-step :complete="!!urlData" step="2" />
            <v-divider></v-divider>
            <v-stepper-step step="3" />
          </v-stepper-header>
        </v-stepper>
      </v-expand-transition>

      <v-fade-transition mode="out-in">
        <enter-contact-details-page
          v-if="step === '1'"
          :loading="loading"
          :offline="offline"
          @proceed="onEnterContactDetailsPageProceed"
        />
        <scan-page
          v-else-if="step === '2'"
          :loading="loading"
          :offline="offline"
          @error="message => onReadError(message)"
          @scanned="onQrCodeScanned"
        />
        <submit-page
          v-else-if="step === '3'"
          :contact-details="contactDetails"
          :visit-data="visitData"
          :offline="offline"
        />
      </v-fade-transition>
    </v-container>

    <v-snackbar v-model="errorSnackbar" multi-line color="primary" top>
      {{ errorSnackbarText }}
    </v-snackbar>

    <v-snackbar v-model="offline" color="primary" bottom timeout="-1">
      <v-icon left>mdi-cloud-off-outline</v-icon>
      {{ $t('no-connection') }}
    </v-snackbar>
  </v-sheet>
</template>

<script>
  import EnterContactDetailsPage from '@/pages/steps/enter-contact-details-page'
  import ScanPage from '@/pages/steps/scan-page'
  import SubmitPage from '@/pages/steps/submit-page'
  import {axios, axiosForHost} from '@/lib/axios'
  import base64Url from 'base64-url'

  export default {
    name: 'register-page',
    components: {SubmitPage, ScanPage, EnterContactDetailsPage},
    data: () => ({
      step: '1',
      contactDetails: null,
      loading: false,
      urlData: null,
      visitData: null,
      errorSnackbar: false,
      errorSnackbarText: '',
      offline: !navigator.onLine || false,
    }),
    computed: {
      colorOfTheDay() {
        return this.visitData?.color_of_the_day
      },
    },
    async mounted() {
      window.addEventListener('online', () => this.offline = false)
      window.addEventListener('offline', () => this.offline = true)

      if (this.$route.query.scan) {
        const data = this.$route.query.scan

        await this.$router.replace({query: {scan: undefined}})

        const urlData = await this.validateRegistrationData(data)

        if (urlData) {
          this.urlData = urlData
          console.log('Got initial QR data')
        } else {
          console.error('URL data check failed')
          this.onReadError(this.$t('invalid-link'))
        }

        setTimeout(() => {
          this.loading = false
        }, 1000)
      }
    },
    methods: {
      async onEnterContactDetailsPageProceed(contactDetails) {
        this.contactDetails = contactDetails

        if (this.urlData) {
          this.loading = true

          try {
            this.visitData = await this.registerVisit(this.urlData)
            this.urlData = null
            this.step = '3'
          } catch (e) {
            this.onReadError()
            console.error(e)
          }

          this.loading = false
        } else {
          this.step = '2'
        }
      },
      async onQrCodeScanned(result) {
        if (this.loading || !result) {
          return
        }

        this.loading = true

        try {
          const url = new URL(result)
          const data = url.searchParams.get('scan')
          const qrData = await this.validateRegistrationData(data)

          if (qrData) {
            this.visitData = await this.registerVisit(qrData)
            this.step = '3'
          } else {
            setTimeout(() => {
              this.loading = false
            }, 1000)
          }
        } catch (e) {
          console.error(e)
          this.onReadError()

          setTimeout(() => {
            this.loading = false
          }, 1000)
        }
      },
      async validateRegistrationData(base64Data) {
        if (!base64Data) {
          return null
        }

        console.log('Checking data:', base64Data)

        try {
          const qrData = JSON.parse(base64Url.decode(base64Data))

          if (!qrData.host || !qrData.data) {
            this.onReadError()
            return null
          }

          // Check if we are allowed to communicate with the host
          const {data: hostConfig} = await axios.get('/config/host', {
            params: {host: qrData.host},
          })

          if (!hostConfig.allowed) {
            this.onReadError(this.$t('check-in-not-possible-here'))
            return null
          }

          this.loading = false
          return qrData
        } catch (e) {
          this.onReadError()
        }

        return null
      },
      async registerVisit(registrationData) {
        const {data: visitData} = await axiosForHost(registrationData.host).post(`/visit/register`, {
          id_data: registrationData.data,
          ...this.contactDetails,
        })

        return visitData
      },
      onReadError(text = this.$t('invalid-qr-code')) {
        this.errorSnackbar = true
        this.errorSnackbarText = text
      },
    },
  }
</script>

<style lang="scss" scoped>
  .full-height {
    display: flex;
    flex-direction: column;
    min-height: 100%;
  }

  .transition-background-color {
    transition: background-color 150ms 200ms;
  }
</style>
