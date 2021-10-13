import Vue from 'vue'
import App from './app.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'
import './registerServiceWorker'
import {translate} from '@/lib/translate'
import BarcodeDetector from "barcode-detector"

// polyfill unless already supported
if (!("BarcodeDetector" in window)) {
  window.BarcodeDetector = BarcodeDetector
}

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
