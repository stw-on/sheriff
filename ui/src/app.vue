<template>
  <v-app>
    <v-main>
      <v-fade-transition mode="out-in">
        <router-view />
      </v-fade-transition>

      <v-container>
        <contact-button />
      </v-container>

      <v-snackbar multi-line v-model="updateAvailable">
        {{ $t('update-available') }}
        <template v-slot:action="{ attrs }">
          <v-btn
            color="white"
            text
            v-bind="attrs"
            @click="reload"
          >
            {{ $t('reload') }}
          </v-btn>
        </template>
      </v-snackbar>
    </v-main>
  </v-app>
</template>

<script>
  import {isUpdateReady, registerServiceWorker, serviceWorkerEventBus} from "@/registerServiceWorker"
  import ContactButton from "@/components/contact-button"

  export default {
    components: {ContactButton},
    data: () => ({
      updateAvailable: isUpdateReady(),
    }),
    beforeMount() {
      serviceWorkerEventBus.$on('updated', () => {
        this.updateAvailable = true
      })

      registerServiceWorker()
    },
    methods: {
      reload() {
        window.location.reload()
      }
    },
  }
</script>
