<template>
  <v-sheet :color="colorOfTheDay" :dark="!!colorOfTheDay" class="fill-height">
    <v-container class="full-height">
      <v-expand-transition>
        <v-stepper v-if="step !== '3'" class="elevation-0 transparent" alt-labels :value="step">
          <v-stepper-header>
            <v-stepper-step step="1" />
            <v-divider></v-divider>
            <v-stepper-step step="2" />
            <v-divider></v-divider>
            <v-stepper-step step="3" />
          </v-stepper-header>
        </v-stepper>
      </v-expand-transition>

      <v-fade-transition mode="out-in">
        <enter-contact-details-page v-if="step === '1'" @proceed="onEnterContactDetailsPageProceed" />
        <scan-page v-else-if="step === '2'" @proceed="onScanPageProceed" />
        <submit-page
          v-else-if="step === '3'"
          :contact-details="contactDetails"
          :qr-data="qrData"
          :visit-data="visitData"
          @proceed="onScanPageProceed"
        />
      </v-fade-transition>
    </v-container>
  </v-sheet>
</template>

<script>
  import EnterContactDetailsPage from '@/pages/steps/enter-contact-details-page'
  import ScanPage from '@/pages/steps/scan-page'
  import SubmitPage from '@/pages/steps/submit-page'

  export default {
    name: 'register-page',
    components: {SubmitPage, ScanPage, EnterContactDetailsPage},
    data: () => ({
      step: '1',
      contactDetails: null,
      qrData: null,
      visitData: null,
    }),
    computed: {
      colorOfTheDay() {
        return this.visitData?.color_of_the_day
      }
    },
    methods: {
      onEnterContactDetailsPageProceed(contactDetails) {
        this.contactDetails = contactDetails
        this.step = '2'
      },
      onScanPageProceed({qrData, visitData}) {
        this.qrData = qrData
        this.visitData = visitData
        this.step = '3'
      },
    },
  }
</script>

<style lang="scss" scoped>
  .full-height {
    min-height: 100%;
    display: flex;
    flex-direction: column;
  }
</style>
