<template>
  <div>
    <h2 class="font-weight-light text-center">Daten erfassen</h2>

    <v-form v-model="isValid">
      <v-row dense>
        <v-col cols="6">
          <v-text-field
            v-model="firstName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Vorname"
          />
        </v-col>
        <v-col cols="6">
          <v-text-field
            v-model="lastName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Nachname"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="street"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Straße + Nr."
          />
        </v-col>
        <v-col cols="4">
          <v-text-field
            v-model="zip"
            :rules="[v => !!v, v => v.length === 5]"
            hide-details
            maxlength="5"
            outlined
            placeholder="PLZ"
          />
        </v-col>
        <v-col cols="8">
          <v-text-field
            v-model="city"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Ort"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="phone"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Telefon- oder Handynummer"
          />
        </v-col>
        <v-col cols="12">
          <v-checkbox
            v-if="!hasSavedContactDetails"
            v-model="saveContactDetails"
            label="Meine Daten für das nächste Mal auf diesem Gerät speichern"
          />
          <a v-else @click="deleteSavedContactDetails">Gespeicherte Daten von diesem Gerät löschen</a>
        </v-col>
        <v-col class="text-center" cols="12">
          <v-btn :disabled="!isValid || offline" :loading="loading" class="mt-4" color="primary" large @click="save">
            <template v-if="offline">
              <v-icon left>mdi-cloud-off-outline</v-icon>
              Offline
            </template>
            <template v-else>
              Weiter
              <v-icon right>mdi-chevron-right</v-icon>
            </template>
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
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
      saveContactDetails: false,
      hasSavedContactDetails: false,
      isValid: false,
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
        } catch (e) {
          console.error(e)
        }
      }
    },
    methods: {
      save() {
        if (this.saveContactDetails) {
          window.localStorage.setItem('sheriff_data', JSON.stringify(this.contactDetails))
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
