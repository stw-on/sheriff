<template>
  <div>
    <div class="scanner-card-container">
      <v-card class="scanner-card" color="black" dark>
        <div class="info-container">
          <v-icon v-if="noCameraFound" size="64">
            mdi-camera-off
          </v-icon>
          <v-progress-circular v-else indeterminate />
        </div>
        <v-fade-transition>
          <qrcode-stream class="qr-video" v-show="showVideo" @init="qrStreamInit" @decode="qrStreamDecoded" />
        </v-fade-transition>
        <div :class="{visible: showVideo}" class="scan-overlay">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </v-card>
    </div>
  </div>
</template>

<script>
  import {QrcodeStream} from 'vue-qrcode-reader'

  export default {
    name: 'qr-scanner',
    components: {
      QrcodeStream,
    },
    props: {
      loading: Boolean,
    },
    data: () => ({
      showVideo: false,
      noCameraFound: false,
    }),
    watch: {
      loading(value) {
        this.showVideo = !value
      },
    },
    methods: {
      async qrStreamInit(promise) {
        try {
          await promise

          if (!this.loading) {
            this.showVideo = true
          }
        } catch (e) {
          this.noCameraFound = true
          this.$emit('error', this.$t('no-camera-access'))
          console.error(e)
        }
      },
      qrStreamDecoded(content) {
        if (this.loading) return
        this.$emit('scanned', content)
      },
    },
  }
</script>

<style lang="scss" scoped>
  .scanner-card-container {
    margin: 0 auto;
    max-width: 66vh;
  }

  .scanner-card {
    overflow: hidden;
    padding-bottom: 100%;
    position: relative;

    .info-container {
      align-items: center;
      bottom: 0;
      display: flex;
      justify-content: center;
      left: 0;
      position: absolute;
      right: 0;
      top: 0;
    }

    .qr-video {
      bottom: 0;
      height: 100%;
      left: 0;
      object-fit: cover;
      position: absolute;
      right: 0;
      top: 0;
      width: 100%;
    }

    .scan-overlay {
      bottom: 0;
      left: 0;
      opacity: 0;
      position: absolute;
      right: 0;
      top: 0;
      $padding: 48px;
      $radius: 16px;
      $width: 8px;
      transform: scale(1.3);

      $curve: cubic-bezier(.35, .98, .34, .99);
      transition: transform 300ms $curve, opacity 300ms $curve;

      &.visible {
        opacity: 0.6;
        transform: scale(1);
      }

      > div {
        border-color: white;
        border-style: solid;
        border-width: 0;
        height: 48px;
        position: absolute;
        width: 48px;
      }

      > div:nth-child(1) {
        border-left-width: $width;
        border-top-left-radius: $radius;
        border-top-width: $width;
        left: $padding;
        top: $padding;
      }

      > div:nth-child(2) {
        border-right-width: $width;
        border-top-right-radius: $radius;
        border-top-width: $width;
        right: $padding;
        top: $padding;
      }

      > div:nth-child(3) {
        border-bottom-left-radius: $radius;
        border-bottom-width: $width;
        border-left-width: $width;
        bottom: $padding;
        left: $padding;
      }

      > div:nth-child(4) {
        border-bottom-right-radius: $radius;
        border-bottom-width: $width;
        border-right-width: $width;
        bottom: $padding;
        right: $padding;
      }
    }
  }
</style>
