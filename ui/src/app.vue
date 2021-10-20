<template>
  <v-app>
    <v-main>
      <v-fade-transition mode="out-in">
        <router-view @show-privacy-policy="showPrivacyPolicy = true" />
      </v-fade-transition>

      <v-container>
        <page-footer @show-privacy-policy="showPrivacyPolicy = true" />
      </v-container>

      <v-dialog v-model="showPrivacyPolicy" fullscreen transition="dialog-bottom-transition">
        <v-card>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click="showPrivacyPolicy = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>{{ $t('privacy-terms') }}</v-toolbar-title>
          </v-toolbar>

          <div class="pa-3" v-html="$t('privacy-policy')"></div>
        </v-card>
      </v-dialog>

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
  import PageFooter from "@/components/page-footer"

  export default {
    components: {PageFooter},
    data: () => ({
      updateAvailable: isUpdateReady(),
      showPrivacyPolicy: false,
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
