<template>
  <div class="full-height">
    <v-fade-transition mode="out-in">
      <v-container v-if="!user && !loading" key="login" class="fill-height justify-center">
        <v-card class="overflow-hidden" max-width="250">
          <div class="pa-3">
            <v-text-field v-model="account" autofocus hide-details placeholder="Benutzername" @keyup.enter="login" />
            <v-text-field
              v-model="password"
              :error-messages="errorMessage"
              placeholder="Passwort"
              type="password"
              @keyup.enter="login"
            />
          </div>
          <v-btn :loading="loading" block color="primary" depressed tile @click="login">Login</v-btn>
        </v-card>
      </v-container>
      <router-view v-else-if="!!user" key="admin" :user="user" />
    </v-fade-transition>
    <v-app-bar v-if="!!user" :value="!$route.meta.hideAppBar" color="primary" dark app class="do-not-print">
      <v-toolbar-title>Verwaltung</v-toolbar-title>
      <v-spacer />
      <v-toolbar-items>
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" text>
              <v-icon :left="$vuetify.breakpoint.mdAndUp">mdi-account</v-icon>
              <span class="hidden-sm-and-down">{{ user.display_name }}</span>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="logout">
              <v-list-item-title>Abmelden</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>
  </div>
</template>

<script>
  import {axios} from '@/lib/axios'

  export default {
    name: 'admin-page',
    data: () => ({
      user: null,
      account: '',
      password: '',
      loading: true,
      errorMessage: null,
    }),

    async mounted() {
      await axios.get('/csrf-cookie')

      try {
        const {data: user} = await axios.get('/session/current')
        this.user = user
      } catch (e) {
        console.log('No user logged in.')
      }

      this.loading = false
    },
    methods: {
      async login() {
        if (!this.account || !this.password) {
          return
        }

        this.loading = true

        try {
          const {data: user} = await axios.post('/session/authenticate', {
            account: this.account,
            password: this.password,
          })

          this.user = user

          setTimeout(() => {
            this.account = ''
            this.password = ''
            this.errorMessage = null
          }, 1000)
        } catch (e) {

          if (e.response) {
            switch (e.response.status) {
              case 401:
                this.errorMessage = 'Falsche Zugangsdaten'
                break
              case 429:
                this.errorMessage = 'Zu viele Versuche'
                break
            }
          }

          console.error(e)
        }

        this.loading = false
      },
      async logout() {
        await axios.post('/session/destroy')
        this.user = null
      },
    },
  }
</script>

<style lang="scss" scoped>
  .do-not-print {
    @media print {
      display: none;
    }
  }

  .full-height {
    height: 100%;
  }
</style>
