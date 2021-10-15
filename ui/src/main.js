import Vue from 'vue'
import App from './app.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'
import {translate} from '@/lib/translate'

Vue.config.productionTip = false

Vue.use({
  install(Vue) {
    Vue.prototype.$t = translate
  },
})

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
