import Vue from 'vue'
import App from './app.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'
import {translate} from '@/lib/translate'
import QrScanner from "qr-scanner"
import qrScannerWorkerSource from '!!raw-loader!../node_modules/qr-scanner/qr-scanner-worker.min.js'

Vue.config.productionTip = false

Vue.use({
  install(Vue) {
    Vue.prototype.$t = translate
  },
})

QrScanner.WORKER_PATH = URL.createObjectURL(new Blob([qrScannerWorkerSource]));

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
