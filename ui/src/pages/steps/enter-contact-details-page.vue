<template>
  <div>
    <h2 class="font-weight-light text-center">{{ $t('enter-data') }}</h2>

    <v-form v-model="isValid">
      <v-row dense>
        <v-col cols="6">
          <v-text-field
            v-model="firstName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            :placeholder="$t('first-name')"
          />
        </v-col>
        <v-col cols="6">
          <v-text-field
            v-model="lastName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            :placeholder="$t('last-name')"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="street"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            :placeholder="$t('street-and-no')"
          />
        </v-col>
        <v-col cols="4">
          <v-text-field
            v-model="zip"
            :rules="[v => !!v, v => v.length === 5]"
            hide-details
            maxlength="5"
            outlined
            :placeholder="$t('zip')"
          />
        </v-col>
        <v-col cols="8">
          <v-text-field
            v-model="city"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            :placeholder="$t('city')"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="phone"
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
            v-if="!hasSavedContactDetails"
            v-model="saveContactDetails"
            :label="$t('save-for-next-time')"
            hide-details
          />
          <a v-else @click="deleteSavedContactDetails">{{ $t('delete-local-data') }}</a>
        </v-col>
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
        <v-col class="text-center" cols="12">
          <div>
            <v-btn
              :disabled="!isValid || offline"
              :loading="loading"
              class="mt-4"
              color="primary"
              large
              @click="proceed"
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
          <div>
            <v-btn v-if="saveContactDetails" :disabled="!isValid || saved" text class="mt-4" color="primary" large @click="save">
              <template v-if="saved">
                <v-icon left>mdi-check</v-icon>
                {{ $t('saved') }}
              </template>
              <template v-else>
                <v-icon left>mdi-content-save-outline</v-icon>
                {{ $t('save-only') }}
              </template>
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-form>

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
  </div>
</template>

<script>
  export default {
    name: 'enter-contact-details-page',
    props: {
      loading: Boolean,
      offline: Boolean,
    },
    data: () => ({
      firstName: '',
      lastName: '',
      street: '',
      zip: '',
      city: '',
      phone: '',
      saveContactDetails: true,
      hasSavedContactDetails: false,
      isValid: false,
      saved: false,
      showPrivacyPolicy: false,
      acceptedPrivacy: false,
    }),
    computed: {
      contactDetails() {
        return {
          first_name: this.firstName,
          last_name: this.lastName,
          street: this.street,
          zip: this.zip,
          city: this.city,
          phone: this.phone,
        }
      },
    },
    watch: {
      contactDetails() {
        this.saved = false
      },
    },
    mounted() {
      const savedData = window.localStorage.getItem('sheriff_data')

      if (savedData) {
        try {
          const data = JSON.parse(savedData)
          this.firstName = data.first_name
          this.lastName = data.last_name
          this.street = data.street
          this.zip = data.zip
          this.city = data.city
          this.phone = data.phone
          this.saveContactDetails = true
          this.hasSavedContactDetails = true
          this.acceptedPrivacy = true

          this.$nextTick(() => this.saved = true)
        } catch (e) {
          console.error(e)
        }
      }
    },
    methods: {
      save() {
        window.localStorage.setItem('sheriff_data', JSON.stringify(this.contactDetails))
        this.saved = true
        this.hasSavedContactDetails = true
      },
      proceed() {
        if (this.saveContactDetails) {
          this.save()
        }

        this.$emit('proceed', this.contactDetails)
      },
      deleteSavedContactDetails() {
        window.localStorage.removeItem('sheriff_data')
        this.hasSavedContactDetails = false
        this.firstName = ''
        this.lastName = ''
        this.street = ''
        this.zip = ''
        this.city = ''
        this.phone = ''
      },
    },
  }
</script>

<style lang="scss" scoped>

</style>
