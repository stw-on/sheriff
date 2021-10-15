<template>
  <v-sheet class="fill-height">
    <v-container class="full-height">
      <v-stepper :value="step" alt-labels class="elevation-0 transparent">
        <v-stepper-header class="elevation-0">
          <v-stepper-step step="1" />
          <v-divider></v-divider>
          <v-stepper-step step="2" />
          <v-divider></v-divider>
          <v-stepper-step step="3" />
        </v-stepper-header>
      </v-stepper>

      <v-scroll-y-reverse-transition mode="out-in">
        <div v-if="step === '1'" key="scan">
          <h2 class="font-weight-light text-center mb-3">{{ $t('scan-covid-certificate') }}</h2>

          <qr-scanner
            :loading="loading"
            :offline="offline"
            @error="showError"
            @scanned="onQrCodeScanned"
          />

          <p class="mt-3 grey--text text--darken-2 text-center">
            {{ $t('scan-covid-certificate-description') }}
          </p>

          <div class="text-center">
            <v-btn @click="selectFile" text outlined>{{ $t('select-screenshot') }}</v-btn>
          </div>
          <input ref="fileInput" class="hidden" type="file" @change="onFileSelected">
        </div>

        <div v-else-if="step === '2'" key="register">
          <h2 class="font-weight-light text-center mb-3">Hi, {{ contactDetails.firstName }}!</h2>

          <p class="mt-3 grey--text text--darken-2 text-center">
            {{ $t('enter-contact-details-prompt') }}
          </p>

          <v-form v-model="contactDetailsAreValid" :disabled="loading">
            <v-row dense>
              <v-col cols="6">
                <v-text-field
                  v-model="contactDetails.firstName"
                  hide-details
                  outlined
                  readonly
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="contactDetails.lastName"
                  hide-details
                  outlined
                  readonly
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="contactDetails.street"
                  :rules="[v => !!v, v => v.length >= 2]"
                  hide-details
                  maxlength="128"
                  outlined
                  :placeholder="$t('street-and-no')"
                />
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="contactDetails.zip"
                  :rules="[v => !!v, v => v.length === 5]"
                  hide-details
                  maxlength="5"
                  outlined
                  :placeholder="$t('zip')"
                />
              </v-col>
              <v-col cols="8">
                <v-text-field
                  v-model="contactDetails.city"
                  :rules="[v => !!v, v => v.length >= 2]"
                  hide-details
                  maxlength="128"
                  outlined
                  :placeholder="$t('city')"
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="contactDetails.phone"
                  :rules="[v => !!v, v => v.length >= 2]"
                  hide-details
                  maxlength="128"
                  outlined
                  :placeholder="$t('phone')"
                />
              </v-col>
            </v-row>
            <v-row no-gutters>
              <v-col cols="12">
                <v-checkbox
                  v-model="acceptedPrivacy"
                  :rules="[v => !!v]"
                >
                  <template v-slot:label>
                    <div>
                      {{ $t('privacy-accepted-1') }}
                      <a href="#" @click.prevent.stop="showPrivacyPolicy = true">{{ $t('privacy-terms') }}</a>
                      {{ $t('privacy-accepted-3') }}
                    </div>
                  </template>
                </v-checkbox>
              </v-col>
            </v-row>
          </v-form>

          <div class="d-flex justify-center mb-12">
            <v-btn
              :disabled="!contactDetailsAreValid || offline"
              :loading="loading"
              class="mt-4"
              color="primary"
              large
              @click="createSignedContactDetails"
            >
              <template v-if="offline">
                <v-icon left>mdi-cloud-off-outline</v-icon>
                {{ $t('offline') }}
              </template>
              <template v-else>
                {{ $t('next') }}
                <v-icon right>mdi-chevron-right</v-icon>
              </template>
            </v-btn>
          </div>
        </div>

        <div v-else-if="step === '3'" key="done">
          <h2 class="font-weight-light text-center mb-3 mt">{{ $t('done') }}!</h2>

          <p class="mt-3 grey--text text--darken-2 text-center">
            {{ $t('registration-done-description') }}
          </p>

          <div class="text-center">
            <v-icon size="96" color="green">
              mdi-check
            </v-icon>
          </div>

          <div class="d-flex justify-center mt-12 mb-12">
            <v-btn
              color="primary"
              large
              exact
              :to="{name: 'checkin'}"
            >
              {{ $t('check-in-now') }}
              <v-icon right>mdi-chevron-right</v-icon>
            </v-btn>
          </div>
        </div>
      </v-scroll-y-reverse-transition>
    </v-container>

    <v-snackbar v-model="errorSnackbar" multi-line color="primary" top>
      {{ errorSnackbarText }}
    </v-snackbar>

    <v-snackbar v-model="offline" color="primary" bottom timeout="-1">
      <v-icon left>mdi-cloud-off-outline</v-icon>
      {{ $t('no-connection') }}
    </v-snackbar>

    <v-dialog v-model="showPrivacyPolicy" fullscreen transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="showPrivacyPolicy = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ $t('privacy-terms') }}</v-toolbar-title>
        </v-toolbar>

        <div class="pa-3" v-html="$t('privacy-policy')"></div>
      </v-card>
    </v-dialog>
  </v-sheet>
</template>

<script>
  import {axios} from "@/lib/axios"
  import QrScanner from "@/components/steps/qr-scanner"

  export default {
    name: 'checkin-page',
    components: {QrScanner},
    data: () => ({
      step: '1',
      loading: false,
      contactDetails: {
        firstName: '',
        lastName: '',
        dateOfBirth: '',
        street: '',
        zip: '',
        city: '',
        phone: '',
      },
      hcert: null,
      acceptedPrivacy: false,
      showPrivacyPolicy: false,
      contactDetailsAreValid: false,
      errorSnackbar: false,
      errorSnackbarText: '',
      offline: !navigator.onLine || false,
    }),
    async mounted() {
      window.addEventListener('online', () => this.offline = false)
      window.addEventListener('offline', () => this.offline = true)
    },
    methods: {
      selectFile() {
        this.$refs.fileInput.click()
      },
      async onFileSelected(event) {
        this.loading = true

        if (event.target.files.length > 0) {
          const barcodeDetector = new window.BarcodeDetector({formats: ["qr_code"]})
          const detectedCodes = await barcodeDetector.detect(event.target.files[0])
          this.$refs.fileInput.value = ''

          if (detectedCodes.length === 0) {
            this.showError(this.$t('no-qr-code-detected'))
            return
          }

          this.loading = false
          await this.onQrCodeScanned(detectedCodes[0].rawValue)
        }

        this.loading = false
      },
      async onQrCodeScanned(result) {
        if (this.loading || !result) {
          return
        }

        this.loading = true

        try {
          const data = await axios.$post('/covpass/check', {
            hcert: result,
          })

          this.hcert = result
          this.contactDetails.firstName = data.first_name
          this.contactDetails.lastName = data.last_name
          this.contactDetails.dateOfBirth = data.date_of_birth
          this.step = '2'

          this.loading = false
        } catch (e) {
          console.error(e)

          this.errorSnackbarText = this.$t('invalid-qr-code')

          switch (e.response?.data?.error) {
            case 'hcert_invalid_signature':
              this.errorSnackbarText = this.$t('error-invalid-signature')
              break
          }

          this.errorSnackbar = true

          setTimeout(() => {
            this.loading = false
          }, 1000)
        }
      },
      async createSignedContactDetails() {
        if (this.loading) {
          return
        }

        this.loading = true

        try {
          const data = await axios.$post('/covpass/sign', {
            hcert: this.hcert,
            street: this.contactDetails.street,
            zip: this.contactDetails.zip,
            city: this.contactDetails.city,
            phone: this.contactDetails.phone,
          })

          window.localStorage.setItem('signedContactDetailsBlob', JSON.stringify(data))
          this.step = '3'

          this.loading = false
        } catch (e) {
          console.error(e)

          setTimeout(() => {
            this.loading = false
          }, 1000)
        }
      },
      showError(text = this.$t('invalid-qr-code')) {
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

  .hidden {
    visibility: hidden;
    position: absolute;
    width: 0;
    height: 0;
  }
</style>
