<template>
  <v-container>
    <h1 v-if="!$route.params.id">Neue Örtlichkeit anlegen</h1>
    <h1 v-else>Örtlichkeit verwalten</h1>

    <v-card class="pa-3" max-width="800">
      <v-row>
        <v-col class="py-0" cols="12" lg="7">
          <v-text-field prepend-icon="mdi-cursor-text" v-model="location.name" hide-details label="Name" />

          <v-select
            v-model="location.public_key_id"
            :items="publicKeys || []"
            :loading="publicKeys === null"
            prepend-icon="mdi-key"
            item-text="name"
            item-value="id"
            label="Schlüssel"
            hide-details
          />
        </v-col>
        <v-col class="py-0" cols="12" lg="5">
          <v-btn :disabled="!$route.params.id" :to="{name: 'admin/location/print', params: {id: $route.params.id}}" block class="mb-2" color="primary" depressed>
            <v-icon left>mdi-qrcode</v-icon>
            QR-Code erzeugen
          </v-btn>
          <v-btn :disabled="!hasPendingChanges || !isValid" block class="mb-2" color="primary" depressed @click="save">
            <v-icon left>mdi-content-save-outline</v-icon>
            Speichern
          </v-btn>
          <v-btn :disabled="!$route.params.id" block color="primary" text>
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
      },
      hasPendingChanges: false,
      publicKeys: null,
    }),
    computed: {
      isValid() {
        return !!this.location.name && !!this.location.public_key_id
      },
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
    },
  }
</script>

<style scoped>

</style>
