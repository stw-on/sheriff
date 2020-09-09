import Vue from 'vue'
import App from './app.vue'
import router from './router'
import vuetify from './plugins/vuetify';
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'

import QrScanner from 'qr-scanner';
import QrScannerWorkerPath from '!!file-loader!../node_modules/qr-scanner/qr-scanner-worker.min.js';

QrScanner.WORKER_PATH = QrScannerWorkerPath;

Vue.config.productionTip = false

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
