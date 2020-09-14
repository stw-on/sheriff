<template>
  <div>
    <h2 class="font-weight-light text-center mb-3">QR-Code scannen</h2>

    <div class="scanner-card-container">
      <v-card color="black" dark class="scanner-card">
        <div class="info-container">
          <v-icon v-if="noCameraFound" size="64">
            mdi-camera-off
          </v-icon>
          <v-progress-circular v-else indeterminate />
        </div>
        <v-fade-transition>
          <video
            v-show="showVideo"
            class="qr-video"
            ref="qrVideo"
            @playing="showVideo = true"
            @emptied="showVideo = false"
          ></video>
        </v-fade-transition>
        <div class="scan-overlay" :class="{visible: showVideo}">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </v-card>
    </div>

    <p class="mt-3 grey--text text-center">
      Bitte scanne nun den QR-Code, der an der Örtlichkeit ausgehängt ist.
    </p>
  </div>
</template>

<script>
  import QrScanner from 'qr-scanner'

  export default {
    name: 'scan-page',
    props: {
      loading: Boolean,
    },
    data: () => ({
      showVideo: false,
      noCameraFound: false,
    }),
    watch: {
      loading(value) {
        if (value) {
          this.qrScanner.start()
        } else {
          this.qrScanner.pause()
          this.showVideo = false
        }
      },
    },
    async mounted() {
      if (!await QrScanner.hasCamera()) {
        this.noCameraFound = true
        this.$emit('error', 'Kein Zugriff auf die Kamera')
        return
      }

      this.qrScanner = new QrScanner(
        this.$refs.qrVideo,
        result => !this.loading && this.$emit('scanned', result),
      )

      try {
        await this.qrScanner.start()
        this.noCameraFound = false
      } catch (e) {
        this.noCameraFound = true
        this.$emit('error', 'Kein Zugriff auf die Kamera')
      }
    },
    beforeDestroy() {
      this.qrScanner.destroy()
    },
  }
</script>

<style lang="scss" scoped>
  .scanner-card-container {
    max-width: 66vh;
    margin: 0 auto;
  }

  .scanner-card {
    padding-bottom: 100%;
    position: relative;
    overflow: hidden;

    .info-container {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .qr-video {
      object-fit: cover;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      height: 100%;
    }

    .scan-overlay {
      opacity: 0;
      transform: scale(1.3);
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
      $padding: 48px;
      $radius: 16px;
      $width: 8px;
      bottom: 0;

      $curve: cubic-bezier(.35, .98, .34, .99);
      transition: transform 300ms $curve, opacity 300ms $curve;

      &.visible {
        opacity: 0.6;
        transform: scale(1);
      }

      > div {
        width: 48px;
        height: 48px;
        border-style: solid;
        border-color: white;
        border-width: 0;
        position: absolute;
      }

      > div:nth-child(1) {
        top: $padding;
        left: $padding;
        border-left-width: $width;
        border-top-width: $width;
        border-top-left-radius: $radius;
      }

      > div:nth-child(2) {
        top: $padding;
        right: $padding;
        border-right-width: $width;
        border-top-width: $width;
        border-top-right-radius: $radius;
      }

      > div:nth-child(3) {
        bottom: $padding;
        left: $padding;
        border-left-width: $width;
        border-bottom-width: $width;
        border-bottom-left-radius: $radius;
      }

      > div:nth-child(4) {
        bottom: $padding;
        right: $padding;
        border-right-width: $width;
        border-bottom-width: $width;
        border-bottom-right-radius: $radius;
      }
    }
  }
</style>
