<template>
  <v-container>
    <page-logo />

    <h2 class="font-weight-light text-center">
      {{
        savedContactDetails
          ? `${$t('welcome-back')}, ${savedContactDetails.first_name}`
          : $t('guest-registration')
      }}
    </h2>

    <template v-if="!!savedContactDetails">
      <p class="text-center">
        {{ $t('ready-to-checkin') }}
      </p>

      <div class="text-center mb-3">
        <v-btn :to="{name: 'checkin'}" color="primary" x-large :disabled="certificateExpired">
          <template v-if="certificateExpired">
            {{ $t('certificate-expired') }}
          </template>
          <template v-else>
            {{ $t('check-in') }}
          </template>
          <v-icon right>
            mdi-chevron-right
          </v-icon>
        </v-btn>
      </div>

      <div class="text-center mb-3">
        {{ $t('certificate-valid-until') }}
        {{ formatDate(savedContactDetails.expires_at) }}.
      </div>

      <div class="text-center mb-3">
        <v-btn text outlined :to="{name: 'register'}">
          {{ $t('register-other-certificate') }}
        </v-btn>
      </div>

      <div class="text-center mb-8">
        <v-btn text @click="deleteRegistration" outlined>
          {{ $t('delete-registration') }}
        </v-btn>
      </div>

      <div class="text-center mb-5">
        <p class="max-width-75">{{ $t('print-checkin-code-description') }}</p>
        <div>
          <v-btn text :to="{name: 'print'}" outlined>
            {{ $t('print-checkin-code') }}
          </v-btn>
        </div>
      </div>
    </template>
    <template v-else>
      <p class="text-center">
        {{ $t('guest-registration-welcome') }}
      </p>

      <div class="text-center mb-5">
        <v-btn :to="{name: 'register'}" color="primary" x-large :disabled="registrationDisabled">
          {{ $t('lets-go') }}
        </v-btn>
        <div v-if="registrationDisabled" class="mt-4 font-weight-bold">
          {{ $t('registration-available-from') }}
        </div>
      </div>
    </template>

    <h2 class="font-weight-light text-center pt-5 pb-3">FAQ</h2>

    <v-expansion-panels accordion>
      <v-expansion-panel v-for="(item, index) in $t('faq')" :key="index">
        <v-expansion-panel-header>{{ item.title }}</v-expansion-panel-header>
        <v-expansion-panel-content>
          <div v-html="item.description"></div>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </v-container>
</template>

<script>
  import {format as formatDate, parseISO, isPast} from "date-fns"
  import {de} from "date-fns/locale"
  import PageLogo from "@/components/page-logo"

  export default {
    name: 'index-page',
    components: {PageLogo},
    data: () => ({
      savedContactDetails: null,
    }),
    computed: {
      registrationDisabled() {
        return window.__sheriff_config?.registration_disabled ?? false
      },
      certificateExpired() {
        if (!this.savedContactDetails) {
          return false
        }

        return isPast(parseISO(this.savedContactDetails.expires_at))
      },
    },
    mounted() {
      try {
        const signedContactDetailsBlob = window.localStorage.getItem('signedContactDetailsBlob')
        if (signedContactDetailsBlob) {
          this.savedContactDetails = JSON.parse(atob(JSON.parse(signedContactDetailsBlob)['blob']))
        }
      } catch (e) {
        console.error(e)
      }
    },
    methods: {
      deleteRegistration() {
        if (confirm(this.$t('delete-registration-prompt'))) {
          window.localStorage.removeItem('signedContactDetailsBlob')
          this.savedContactDetails = null
        }
      },
      formatDate(isoString) {
        return formatDate(parseISO(isoString), 'dd.MM.yyyy', {
          locale: de,
        })
      },
    }
  }
</script>

<style lang="scss" scoped>
  .max-width-75 {
    max-width: 75vw;
    display: inline-block;
  }
</style>
