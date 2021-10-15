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
        <template v-if="!!scanError">
          <div class="text-pre">{{ scanError }}</div>
        </template>
        <template v-else>
          <h2>{{ scanResult.last_name }}, {{ scanResult.first_name }}</h2>
          <h3>{{ formatDate(scanResult.entered_at) }}, {{ scanResult.location_name }}</h3>
          <div>{{ scanResult.date_of_birth }}</div>
          <div>{{ scanResult.street }}</div>
          <div>{{ scanResult.zip }} {{ scanResult.city }}</div>
          <div>{{ scanResult.phone }}</div>
        </template>
      </v-card>
    </v-scroll-x-reverse-transition>
  </v-container>
</template>

<script>
  import QrScanner from "@/components/steps/qr-scanner"
  import {axios} from "@/lib/axios"
  import {Base64} from 'js-base64'
  import Sodium from 'sodium-javascript'
  import {differenceInMinutes, format as formatDate, parseISO} from 'date-fns'
  import {de} from 'date-fns/locale'


  export default {
    name: "scanner-page",
    components: {QrScanner},
    data: () => ({
      loading: true,
      signingKeys: {},
      scanError: null,
      scanResult: null,
      scanId: 0,
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

        if (differenceInMinutes(new Date(), parseISO(this.scanResult.entered_at)) > 20) {
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
      onQrCodeScanned(result) {
        this.scanId++

        try {
          const data = JSON.parse(result)

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
        } catch (e) {
          console.error(e)
          this.scanResult = null
          this.scanError = 'Ungültiger QR-Code'
        }

        window.navigator.vibrate?.(100)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .result {
    font-size: 1.3em;
    line-height: 1.3;
  }
</style>
