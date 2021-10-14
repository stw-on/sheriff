<template>
  <v-container>
    <div class="d-flex flex-wrap">
      <h1>Örtlichkeiten verwalten</h1>
      <v-spacer />
      <v-btn :disabled="!user.can_manage_locations" :to="{name: 'admin/location'}" color="primary">
        <v-icon left>mdi-plus</v-icon>
        Hinzufügen
      </v-btn>
    </div>

    <v-progress-circular v-if="!locations" indeterminate />
    <v-data-table
      v-else
      :headers="[
        {text: 'Name', value: 'name'},
        {text: 'Registrierungen heute', value: 'visits_today'},
        {text: 'Eintritt erlaubt für', value: 'allowed_certifications'},
      ]"
      :items="locations"
      @click:row="edit"
    >
      <template #item.allowed_certifications="{item}">
        {{ allowedCertificationsToString(item.allowed_certifications) }}
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
  import {axios} from '@/lib/axios'

  export default {
    name: 'admin-index-page',
    props: {
      user: Object,
    },
    data: () => ({
      locations: null,
    }),
    async mounted() {
      setInterval(this.loadLocations, 20000)
      await this.loadLocations()
    },
    methods: {
      async loadLocations() {
        const {data} = await axios.get('/location')
        this.locations = data
      },
      edit(entity) {
        this.$router.push({name: `admin/location`, params: {id: entity.id}})
      },
      allowedCertificationsToString(allowedCertifications) {
        return Object.entries({
          0b001: 'Geimpft',
          0b010: 'Getestet',
          0b100: 'Genesen',
        })
          .filter(v => allowedCertifications & v[0])
          .map(v => v[1])
          .join(', ')
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>
