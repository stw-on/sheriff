<template>
  <div class="card-container">
    <v-card class="d-inline-block" elevation="12" light min-width="60%">
      <div class="pa-5 text-center">
        <v-icon color="success" size="72">
          mdi-check
        </v-icon>

        <h1 class="font-weight-bold">{{ visitData.location_name }}</h1>
        <h1 class="font-weight-bold">{{ visitData.entered_at }}</h1>

        <div class="contact-details mt-4">
          {{ contactDetails.first_name }} {{ contactDetails.last_name }}<br>
          {{ contactDetails.street }}<br>
          {{ contactDetails.zip }} {{ contactDetails.city }}<br>
          {{ contactDetails.phone }}
        </div>
      </div>
    </v-card>

    <p class="mt-5 text-center">
      Bitte lass' diese Seite geöffnet. Es kann sein, dass du sie in der Örtlichkeit vorzeigen musst.
    </p>
  </div>
</template>

<script>
  export default {
    name: 'submit-page',
    props: {
      visitData: Object,
      contactDetails: Object,
    },
    data: () => ({

    }),
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
    font-size: small;
    line-height: 1.3;
  }
</style>
