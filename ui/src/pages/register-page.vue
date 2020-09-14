<template>
  <v-sheet :color="colorOfTheDay" :dark="!!colorOfTheDay" class="fill-height transition-background-color">
    <v-container class="full-height">
      <v-expand-transition>
        <v-stepper v-if="step !== '3'" class="elevation-0 transparent" alt-labels :value="step">
          <v-stepper-header>
            <v-stepper-step step="1" />
            <v-divider></v-divider>
            <v-stepper-step step="2" />
            <v-divider></v-divider>
            <v-stepper-step step="3" />
          </v-stepper-header>
        </v-stepper>
      </v-expand-transition>

      <v-fade-transition mode="out-in">
        <enter-contact-details-page
          v-if="step === '1'"
          @proceed="onEnterContactDetailsPageProceed"
          :loading="loading"
        />
        <scan-page
          v-else-if="step === '2'"
          :loading="loading"
          @scanned="onQrCodeScanned"
          @error="message => onReadError(message)"
        />
        <submit-page
          v-else-if="step === '3'"
          :contact-details="contactDetails"
          :visit-data="visitData"
        />
      </v-fade-transition>
    </v-container>

    <v-snackbar top color="primary" v-model="errorSnackbar">
      {{ errorSnackbarText }}
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
    }),
    computed: {
      colorOfTheDay() {
        return this.visitData?.color_of_the_day
      },
    },
    async mounted() {
      if (this.$route.query.scan) {
        const data = this.$route.query.scan

        await this.$router.replace({query: {scan: undefined}})

        const urlData = await this.validateRegistrationData(data)

        if (urlData) {
          this.urlData = urlData
          console.log('Got initial QR data')
        } else {
          console.error('URL data check failed')
          this.onReadError('Ungültiger Link')
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
          this.visitData = await this.registerVisit(this.urlData)
          this.urlData = null
          this.loading = false
          this.step = '3'
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
            this.onReadError('Du kannst dich an dieser Örtlichkeit nicht über diese Webseite anmelden')
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
      onReadError(text = 'Der QR-Code ist ungültig') {
        this.errorSnackbar = true
        this.errorSnackbarText = text
      },
    },
  }
</script>

<style lang="scss" scoped>
  .full-height {
    min-height: 100%;
    display: flex;
    flex-direction: column;
  }

  .transition-background-color {
    transition: background-color 150ms 200ms;
  }
</style>
