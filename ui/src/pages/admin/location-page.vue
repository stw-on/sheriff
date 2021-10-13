<template>
  <v-container>
    <h1 v-if="!$route.params.id">Neue Örtlichkeit anlegen</h1>
    <h1 v-else>Örtlichkeit verwalten</h1>

    <v-card class="px-5 py-8" max-width="800">
      <v-row>
        <v-col class="py-0" cols="12" lg="7">
          <v-text-field prepend-icon="mdi-cursor-text" v-model="location.name" outlined label="Name" />

          <v-select
            v-model="location.public_key_id"
            :items="publicKeys || []"
            :loading="publicKeys === null"
            prepend-icon="mdi-key"
            item-text="name"
            item-value="id"
            label="Schlüssel"
            outlined
          />

          <v-select
            v-model="allowedCertifications"
            label="Eintritt erlauben für"
            :items="[
              {text: 'Geimpft', value: 0b001},
              {text: 'Getestet', value: 0b010},
              {text: 'Genesen', value: 0b100},
            ]"
            prepend-icon="mdi-walk"
            multiple
            outlined
            hide-details
          />
        </v-col>
        <v-col class="py-0" cols="12" lg="5">
          <v-btn :disabled="!$route.params.id" @click="generatePdf" block class="mb-2" color="primary" depressed>
            <v-icon left>mdi-qrcode</v-icon>
            QR-Code erzeugen
          </v-btn>
          <v-btn :disabled="!hasPendingChanges || !isValid" block class="mb-2" color="primary" depressed @click="save">
            <v-icon left>mdi-content-save-outline</v-icon>
            Speichern
          </v-btn>
          <v-btn disabled block color="primary" text>
            <v-icon left>mdi-delete-outline</v-icon>
            Löschen
          </v-btn>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
  import {axios} from '@/lib/axios'

  export default {
    name: 'location-page',
    data: vm => ({
      editing: !vm.$route.params.id,
      location: {
        name: '',
        public_key_id: null,
        allowed_certifications: 0,
      },
      hasPendingChanges: false,
      publicKeys: null,
    }),
    computed: {
      isValid() {
        return !!this.location.name && !!this.location.public_key_id
      },
      allowedCertifications: {
        set(value) {
          this.location.allowed_certifications = value.reduce((acc, v) => acc | v, 0)
        },
        get() {
          return [0b001, 0b010, 0b100].filter(v => this.location.allowed_certifications & v)
        },
      }
    },
    watch: {
      location: {
        deep: true,
        handler() {
          this.hasPendingChanges = true
        },
      },
    },
    async mounted() {
      if (this.$route.params.id) {
        const {data} = await axios.get(`/location/${this.$route.params.id}`)
        this.location = data
        this.$nextTick(() => this.hasPendingChanges = false)
      }

      const {data} = await axios.get('/public-key')
      this.publicKeys = data
    },
    methods: {
      async save() {
        try {
          const {data} = await axios.put(`/location`, {
            ...this.location,
            ...this.$route.params.id
              ? {id: this.$route.params.id}
              : {},
          })

          this.location = data
          this.$nextTick(() => this.hasPendingChanges = false)

          if (this.$route.params.id !== data.id) {
            this.$router.replace({params: {id: data.id}})
          }
        } catch (e) {
          console.error(e)
        }
      },
      async generatePdf() {
        const pdfWindow = window.open()

        pdfWindow.document.documentElement.innerHTML = 'PDF wird geladen...'

        try {
          const {data} = await axios.get(`/location/${this.$route.params.id}/pdf`, {
            responseType: 'blob',
          })

          pdfWindow.location.href = window.URL.createObjectURL(new Blob([data], {type: 'application/pdf'}))
        } catch (e) {
          console.error(e)
          pdfWindow.document.documentElement.innerHTML = 'Ein Fehler ist aufgetreten.'
        }
      },
    },
  }
</script>

<style scoped>

</style>
