import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import de from 'vuetify/es5/locale/de'

Vue.use(Vuetify)

export default new Vuetify({
  theme: {
    options: {
      customProperties: true,
    },
    themes: {
      light: {
        primary: '#e30712',
        secondary: '#c00b0b',
        accent: '#e30712',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107',
      },
    },
  },
  lang: {
    locales: {de},
    current: 'de',
  },
})
