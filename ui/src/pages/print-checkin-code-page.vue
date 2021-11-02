<template>
  <v-container>
    <div class="d-flex justify-center">
      <v-card class="qr-code pa-4" outlined v-html="checkinQrHtml" />
    </div>

    <div class="text-center mt-8">
      <strong>{{ `${savedContactDetails.last_name}, ${savedContactDetails.first_name}` }}</strong>
    </div>

    <div class="text-center mt-3">
      {{ $t('certificate-valid-until') }}
      {{ formatDate(savedContactDetails.expires_at) }}.
    </div>
    <div class="text-center mt-3">
      {{ $t('print-notice') }}
    </div>

    <div class="mt-8 text-center">
      <page-logo max-width="45%" />
    </div>
  </v-container>
</template>

<script>
  import QRCode from "qrcode-svg"
  import {format as formatDate, parseISO} from "date-fns"
  import {de} from "date-fns/locale"
  import PageLogo from "@/components/page-logo"

  export default {
    name: "print-checkin-code-page",
    components: {PageLogo},
    data: () => ({
      savedContactDetails: {},
      checkinQrHtml: '',
    }),
    async mounted() {
      const signedContactDetailsBlob = window.localStorage.getItem('signedContactDetailsBlob')
      if (signedContactDetailsBlob) {
        this.savedContactDetails = JSON.parse(atob(JSON.parse(signedContactDetailsBlob)['blob']))
      }

      this.checkinQrHtml = new QRCode({
        content: JSON.stringify({
          type: 'checkin',
          data: JSON.parse(signedContactDetailsBlob),
        }),
        ecl: 'M',
        join: true,
        container: 'svg-viewbox',
        padding: 0,
      }).svg()

      await this.$nextTick()

      // I love browsers
      requestAnimationFrame(() => {
        setTimeout(() => {
          print()
        }, 200)
      })
    },
    methods: {
      formatDate(isoString) {
        if (!isoString) return
        return formatDate(parseISO(isoString), 'dd.MM.yyyy', {
          locale: de,
        })
      },
    },
  }
</script>

<style lang="scss">
  @media print {
    @page {
      size: A4;
    }
  }

  svg {
    border-radius: 0 !important;
  }
</style>

<style lang="scss" scoped>
  .qr-code {
    width: 200px;
    height: 200px;

    @media print {
      width: 40vw;
      height: 40vw;
    }
  }

  .logo {
    max-width: 50vw;
  }
</style>
