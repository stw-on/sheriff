<template>
  <div>
    <h2 class="font-weight-light text-center mb-3">QR-Code scannen</h2>

    <v-card color="black" dark class="scanner-card">
      <div class="info-container">
        <v-progress-circular indeterminate />
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

    <p class="mt-3 grey--text text-center">
      Bitte scanne nun den QR-Code, der an der Örtlichkeit ausgehängt ist.
    </p>

    <v-snackbar top color="primary" v-model="errorSnackbar">
      {{ errorSnackbarText }}
    </v-snackbar>
  </div>
</template>

<script>
  import QrScanner from 'qr-scanner'
  import {axios, axiosForHost} from '@/lib/axios'

  export default {
    name: 'scan-page',
    data: () => ({
      showVideo: false,
      loading: false,
      errorSnackbar: false,
      errorSnackbarText: '',
    }),
    mounted() {
      this.qrScanner = new QrScanner(this.$refs.qrVideo, this.onQrCodeScanned)
      this.qrScanner.start()
    },
    beforeDestroy() {
      this.qrScanner.destroy()
    },
    methods: {
      async onQrCodeScanned(result) {
        if (this.loading || !result) {
          return
        }

        this.loading = true
        this.qrScanner.pause()
        this.showVideo = false

        console.log('Got scan result:', result)

        try {
          const qrData = JSON.parse(result)

          if (!qrData.host || !qrData.data) {
            this.onReadError()
            return
          }

          // Check if we are allowed to communicate with the host
          const {data: hostConfig} = await axios.get('/config/host', {
            params: {host: qrData.host},
          })

          if (!hostConfig.allowed) {
            this.onReadError('Du kannst dich an dieser Örtlichkeit nicht über diese Webseite anmelden')
            return
          }

          const {data: visitData} = await axiosForHost(qrData.host).post(`/visit/register`, {
            id_data: qrData.data,
          })

          this.$emit('proceed', {
            qrData,
            visitData,
          })
        } catch (e) {
          this.onReadError()
        }

        this.loading = false
      },
      onReadError(text = 'Der QR-Code ist ungültig') {
        this.errorSnackbar = true
        this.errorSnackbarText = text

        setTimeout(() => {
          this.loading = false
          this.qrScanner.start()
        }, 1000)
      },
    },
  }
</script>

<style lang="scss" scoped>
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
