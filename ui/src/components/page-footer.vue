<template>
  <div class="mt-8 pt-8 text-center contact-button">
    <div>{{ $t('need-help') }}</div>

    <v-btn text @click="sendMail">
      <v-icon left>
        mdi-help-circle-outline
      </v-icon>
      {{ $t('send-email') }}
    </v-btn>

    <div class="d-block mt-4">
      <a v-if="privacyUrl === null" href="#" @click.prevent.stop="$emit('show-privacy-policy')" class="mx-4 legal-link">
        {{ $t('privacy') }}
      </a>
      <a v-else :href="privacyUrl" target="_blank" class="mx-4 legal-link">
        {{ $t('privacy') }}
      </a>

      <a v-if="!!imprintUrl" :href="imprintUrl" target="_blank" class="mx-4 legal-link">
        {{ $t('imprint') }}
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    name: "page-footer",
    computed: {
      privacyUrl() {
        return window.__sheriff_config?.privacy_url ?? null
      },
      imprintUrl() {
        return window.__sheriff_config?.imprint_url ?? null
      },
    },
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
          `has signed blob: ${window.localStorage.getItem('signedContactDetailsBlob') ? 'yes' : 'no'}`
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

  .legal-link {
    color: #aaa;
    text-decoration: none;
  }
</style>
