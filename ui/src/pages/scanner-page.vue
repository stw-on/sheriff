<template>
  <v-container>
    <qr-scanner
      :loading="loading"
      @scanned="onQrCodeScanned"
      class="mb-2"
    />

    <v-scroll-x-reverse-transition mode="out-in">
      <v-card
        v-if="scanResult || scanError"
        class="pa-3 result"
        :color="cardColor"
        dark
        :key="scanId"
      >
        <div class="d-flex justify-end">
          <v-btn icon @click="scanError = null; scanResult = null">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </div>
        <template v-if="!!scanError">
          <div class="text-pre">{{ scanError }}</div>
        </template>
        <template v-else-if="scanType === 'confirmation'">
          <h2>{{ scanResult.last_name }}, {{ scanResult.first_name }}</h2>
          <h3>{{ formatDate(scanResult.entered_at) }}, {{ scanResult.location_name }}</h3>
          <div>{{ scanResult.date_of_birth }}</div>
          <div>{{ scanResult.street }}</div>
          <div>{{ scanResult.zip }} {{ scanResult.city }}</div>
          <div>{{ scanResult.phone }}</div>
        </template>
        <template v-else-if="scanType === 'checkin'">
          <h2>{{ scanResult.last_name }}, {{ scanResult.first_name }}<v-icon right>mdi-check</v-icon></h2>
          <h3>{{ scanResult.location_name }}</h3>
          <div>{{ scanResult.date_of_birth }}</div>
          <div>{{ scanResult.street }}</div>
          <div>{{ scanResult.zip }} {{ scanResult.city }}</div>
          <div>{{ scanResult.phone }}</div>
        </template>
        <template v-else-if="scanType === 'location_identifier'">
          <h2><v-icon left>mdi-check</v-icon> Einrichtung verknüpft</h2>
          <div>Du kannst nun QR-Codes von Gästen scannen.</div>
        </template>
      </v-card>
    </v-scroll-x-reverse-transition>
  </v-container>
</template>

<script>
  import QrScanner from "@/components/steps/qr-scanner"
  import {axios, axiosForHost} from "@/lib/axios"
  import {Base64} from 'js-base64'
  import Sodium from 'sodium-javascript'
  import {differenceInMinutes, format as formatDate, parseISO} from 'date-fns'
  import {de} from 'date-fns/locale'

  export default {
    name: "scanner-page",
    components: {QrScanner},
    data: () => ({
      loading: true,
      lastResult: null,
      signingKeys: {},
      scanError: null,
      scanResult: null,
      scanType: null,
      scanId: 0,
      locationIdentifier: null,
    }),
    async mounted() {
      const {data: keys} = await axios.get('/signing-key')
      this.signingKeys = keys

      this.loading = false
    },
    computed: {
      cardColor() {
        if (this.scanError || !this.scanResult) {
          return 'error'
        }

        if (typeof this.scanResult === 'object' && differenceInMinutes(new Date(), parseISO(this.scanResult.entered_at)) > 20) {
          return 'warning'
        }

        return 'success'
      }
    },
    methods: {
      formatDate(isoString) {
        return formatDate(parseISO(isoString), 'dd.MM.yyyy hh:mm', {
          locale: de,
        })
      },
      async onQrCodeScanned(result) {
        if (this.lastResult === result) {
          return
        }
        this.lastResult = result

        this.scanId++

        try {
          try {
            const url = new URL(result)

            if (!url.searchParams.get('scan')) {
              throw new Error('Not a checkin URL')
            }

            this.locationIdentifier = url.searchParams.get('scan')
            this.scanError = null
            this.scanType = 'location_identifier'
            this.scanResult = true
            return
          } catch (e) {
            // It's not a URL...
          }

          const data = JSON.parse(result)

          if (data.type === 'checkin') {
            if (!this.locationIdentifier) {
              this.showError('Bitte zuerst QR-Code der Einrichtung scannen.')
              return
            }

            this.scanType = 'checkin'
            if (!await this.registerVisit(data.data, this.locationIdentifier)) {
              return
            }
          } else {
            const publicKeyBase64 = this.signingKeys[data.key_id]
            if (!publicKeyBase64) {
              this.scanError = 'Ungültige Signatur\n(Schlüssel nicht gefunden)'
              return
            }

            const signature = Base64.toUint8Array(data.signature)
            const dataRaw = Base64.toUint8Array(data.blob)
            const publicKey = Base64.toUint8Array(publicKeyBase64)
            if (!Sodium.crypto_sign_verify_detached(signature, dataRaw, publicKey)) {
              this.scanError = 'Ungültige Signatur'
              return
            }

            this.scanResult = JSON.parse(Base64.atob(data.blob))
            this.scanError = null
            this.scanType = 'confirmation'
            window.navigator.vibrate?.(100)
          }
        } catch (e) {
          console.error(e)
          this.showError('Ungültiger QR-Code')
          window.navigator.vibrate?.([75, 50, 75, 50, 75])
        }
      },
      showError(message) {
        this.scanResult = null
        this.scanError = message
      },
      async validateRegistrationData(locationIdentifier) {
        if (!locationIdentifier) {
          return null
        }

        console.log('Checking data:', locationIdentifier)

        try {
          const qrData = JSON.parse(Base64.decode(locationIdentifier))

          if (!qrData.host || !qrData.data) {
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
          this.showError(this.$t('error-network-wifi'), e.response?.status)
        }

        return null
      },
      async registerVisit(signedContactDetails, locationIdentifier) {
        try {
          const registrationData = await this.validateRegistrationData(locationIdentifier)

          if (registrationData) {
            const {data: visitData} = await axiosForHost(registrationData.host).post(`/visit/register`, {
              id_data: registrationData.data,
              signed_contact_details: signedContactDetails,
            })
            this.scanResult = visitData
            return visitData
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
              this.showError(this.$t('error-network-wifi'))
              break
          }
        }
      },
    }
  }
</script>

<style lang="scss" scoped>
  .result {
    font-size: 1.3em;
    line-height: 1.3;
  }
</style>
