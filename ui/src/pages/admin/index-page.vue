<template>
  <v-container>
    <div class="d-flex">
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
      ]"
      :items="locations"
      @click:row="edit"
    />
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
    }
  }
</script>

<style lang="scss" scoped>

</style>
