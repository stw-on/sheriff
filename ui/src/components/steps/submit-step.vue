<template>
  <div class="card-container">
    <v-card class="d-inline-block overflow-hidden" elevation="12" light min-width="60%">
      <div class="pa-5 text-center">
        <v-icon color="success" size="72">
          mdi-check
        </v-icon>

        <h1 class="font-weight-bold pt-2 line-height-1">{{ visitData.entered_at }}</h1>
        <h1 class="font-weight-light">{{ visitData.location_name }}</h1>
      </div>
      <v-divider />
      <div class="text-center white--text">
        <div
          v-if="!!contactDetails"
          :style="{backgroundColor: visitData.color_of_the_day}"
          class="pa-5 contact-details"
        >
          <span class="text-uppercase font-weight-bold">{{ contactDetails.first_name }} {{
              contactDetails.last_name
            }}</span><br>
          {{ contactDetails.street }}<br>
          {{ contactDetails.zip }} {{ contactDetails.city }}<br>
          {{ contactDetails.phone }}
        </div>
      </div>
    </v-card>

    <p class="mt-5 pt-3 text-center">
      {{ $t('keep-open-prompt') }}
    </p>
  </div>
</template>

<script>
  export default {
    name: 'submit-step',
    props: {
      visitData: Object,
      contactDetails: Object,
    },
    data: () => ({}),
  }
</script>

<style lang="scss" scoped>
  .card-container {
    align-items: center;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: center;
  }

  .contact-details {
    line-height: 1.3;
    z-index: 1;
    opacity: 0.9;
  }

  .position-relative {
    position: relative;
  }

  .line-height-1 {
    line-height: 1;
  }
</style>
