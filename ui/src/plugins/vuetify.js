import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import de from 'vuetify/es5/locale/de'
import tinycolor from 'tinycolor2'

Vue.use(Vuetify)

export default new Vuetify({
  theme: {
    options: {
      customProperties: true,
    },
    themes: {
      light: {
        primary: window.__sheriff_config?.theme_color ?? '#e30712',
        secondary: tinycolor(window.__sheriff_config?.theme_color ?? '#e30712').darken(7).toString(),
        accent: window.__sheriff_config?.theme_color ?? '#e30712',
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
