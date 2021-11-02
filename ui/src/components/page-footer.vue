<template>
  <div class="mt-8 pt-8 text-center contact-button">
    <div>{{ $t('need-help') }}</div>

    <v-btn text @click="sendMail">
      <v-icon left>
        mdi-help-circle-outline
      </v-icon>
      {{ $t('send-email') }}
    </v-btn>

    <a href="#" @click.prevent.stop="$emit('show-privacy-policy')" class="d-block mt-4 privacy-policy">
      {{ $t('privacy') }}
    </a>
  </div>
</template>

<script>
  export default {
    name: "page-footer",
    methods: {
      sendMail() {
        window.open(`mailto:${this.$t('target-email')}?subject=${encodeURIComponent('Check In')}&body=${encodeURIComponent(this.getMailBody())}`)
      },
      getMailBody() {
        return `${this.$t('describe-your-problem')}:\n` +
          `\n` +
          `\n` +
          `${this.$t('it-info')}:\n` +
          `\n` +
          `path: ${window.location.pathname}\n` +
          `user agent: ${window.navigator.userAgent}\n` +
          `signed blob: ${window.localStorage.getItem('signedContactDetailsBlob') ?? 'null'}`
      },
    },
  }
</script>

<style lang="scss" scoped>
  .contact-button {
    border-top: 1px solid rgba(0, 0, 0, 0.1);

    @media print {
      display: none;
    }
  }

  .privacy-policy {
    color: #aaa;
    text-decoration: none;
  }
</style>
