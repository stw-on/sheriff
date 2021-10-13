<template>
  <v-sheet :style="{backgroundColor: colorOfTheHour}" class="fill-height transition-background-color">
    <v-container class="full-height">
      <v-expand-transition>
        <v-stepper v-if="step !== '2'" :value="step" alt-labels class="elevation-0 transparent">
          <v-stepper-header class="elevation-0">
            <v-stepper-step step="1" />
            <v-divider></v-divider>
            <v-stepper-step step="2" />
          </v-stepper-header>
        </v-stepper>
      </v-expand-transition>

      <v-scroll-y-reverse-transition mode="out-in">
        <div v-if="step === '1'" key="scan">
          <h2 class="font-weight-light text-center mb-3">{{ $t('scan-qr-code') }}!</h2>

          <qr-scanner
            :loading="loading"
            :offline="offline"
            @error="showError"
            @scanned="onQrCodeScanned"
          />

          <p class="mt-3 grey--text text--darken-2 text-center">
            {{ $t('scan-qr-code-prompt') }}
          </p>
        </div>

        <v-sheet
          dark
          color="transparent"
          v-else-if="step === '2'"
          class="d-flex flex-column flex-grow-1 align-center justify-center"
          key="done"
        >
          <div class="text-center">
            <div class="big mb-10">{{ visitData.location_name }}</div>
            <div ref="time" class="time mb-10">{{ visitData.entered_at }}</div>
            <div class="big">{{ visitData.last_name }},</div>
            <div class="big">{{ visitData.first_name }}</div>
            <div class="headline">{{ visitData.date_of_birth }}</div>
            <div class="headline mt-2">{{ visitData.street }}</div>
            <div class="headline">{{ visitData.zip }} {{ visitData.city }}</div>
            <div class="headline">{{ visitData.phone }}</div>

            <v-icon size="100vw" class="overlay-icon">
              mdi-{{ iconOfTheHour }}
            </v-icon>
          </div>
        </v-sheet>
      </v-scroll-y-reverse-transition>
    </v-container>

    <v-snackbar v-model="errorSnackbar" multi-line color="primary" top>
      {{ errorSnackbarText }}
    </v-snackbar>

    <v-snackbar v-model="keepOpenSnackbar" multi-line top timeout="7000">
      <div class="font-weight-bold">{{ $t('keep-open-prompt') }}</div>
    </v-snackbar>

    <v-snackbar v-model="offline" color="primary" bottom timeout="-1">
      <v-icon left>mdi-cloud-off-outline</v-icon>
      {{ $t('no-connection') }}
    </v-snackbar>
  </v-sheet>
</template>

<script>
  import {axios, axiosForHost} from "@/lib/axios"
  import QrScanner from "@/components/steps/qr-scanner"
  import base64Url from "base64-url"
  import {confettiFromElement} from "@/lib/confettiFromElement"

  export default {
    name: 'checkin-page',
    components: {QrScanner},
    data: () => ({
      step: '1',
      loading: false,
      errorSnackbar: false,
      errorSnackbarText: '',
      visitData: null,
      keepOpenSnackbar: false,
      offline: !navigator.onLine || false,
    }),
    computed: {
      colorOfTheHour() {
        return this.visitData?.color_of_the_hour
      },
      iconOfTheHour() {
        return this.visitData?.icon_of_the_hour
      },
    },
    mounted() {
      window.addEventListener('online', () => this.offline = false)
      window.addEventListener('offline', () => this.offline = true)

      if (!window.localStorage.getItem('signedContactDetailsBlob')) {
        this.$router.replace({name: 'index'})
        return
      }

      if (this.$route.query.scan) {
        this.registerVisit(this.$route.query.scan)
      }
    },
    methods: {
      showError(text = this.$t('invalid-qr-code')) {
        this.errorSnackbar = true
        this.errorSnackbarText = text
      },
      async onQrCodeScanned(result) {
        if (this.loading || !result) {
          return
        }

        this.loading = true

        try {
          const url = new URL(result)
          const data = url.searchParams.get('scan')

          await this.registerVisit(data)
        } catch (e) {
          console.error(e)
          this.showError()
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
            this.showError()
            return null
          }

          // Check if we are allowed to communicate with the host
          const {data: hostConfig} = await axios.get('/config/host', {
            params: {host: qrData.host},
          })

          if (!hostConfig.allowed) {
            this.showError(this.$t('check-in-not-possible-here'))
            return null
          }

          return qrData
        } catch (e) {
          this.showError()
        }

        return null
      },
      async registerVisit(locationIdentifier) {
        try {
          const registrationData = await this.validateRegistrationData(locationIdentifier)

          if (registrationData) {
            const {data: visitData} = await axiosForHost(registrationData.host).post(`/visit/register`, {
              id_data: registrationData.data,
              signed_contact_details: JSON.parse(window.localStorage.getItem('signedContactDetailsBlob')),
            })
            this.visitData = visitData
            this.step = '2'

            setTimeout(() => {
              this.keepOpenSnackbar = true

              if (this.visitData.entered_at === '13:37') {
                confettiFromElement(this.$refs.time, {
                  disableForReducedMotion: true,
                  zIndex: 100000,
                })
              }
            }, 1000)
          } else {
            this.showError()
            setTimeout(() => {
              this.loading = false
            }, 1000)
          }

        } catch (e) {
          console.error(e)

          switch (e.response?.data?.error) {
            case 'hcert_not_covered':
              this.showError(this.$t('error-certificate-not-covered'))
              break
            default:
              this.showError()
              break
          }
        }
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
    transition: background-color 300ms;
  }

  .big {
    font-weight: bold;
    font-size: 3em;
    line-height: 1;
  }

  @keyframes scale {
    from {
      transform: scale(0.9);
    }
    to {
      transform: scale(1.1);
    }
  }

  .time {
    font-weight: bold;
    font-size: 5em;
    line-height: 1;
    animation: scale 1s infinite alternate;
  }

  @keyframes rotate {
    from {
      transform: translateX(-50%) translateY(-50%) rotate(0deg);
    }
    to {
      transform: translateX(-50%) translateY(-50%) rotate(360deg);
    }
  }

  .overlay-icon {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translateX(-50%) translateY(-50%);
    opacity: 0.2;
    animation: rotate 5s infinite linear;
  }
</style>