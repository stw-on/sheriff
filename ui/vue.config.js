module.exports = {
  transpileDependencies: [
    'vuetify',
  ],
  devServer: {
    public: 'sheriff.localhost:80',
  },
  pwa: {
    name: 'Check-In',
    themeColor: '#ffffff',
    msTileColor: '#000000',
    appleMobileWebAppCapable: 'yes',
    iconPaths: {
      favicon32: 'img/icons/favicon-32x32.png',
      favicon16: 'img/icons/favicon-16x16.png',
      appleTouchIcon: 'img/icons/apple-touch-icon.png',
      maskIcon: 'img/icons/safari-pinned-tab.svg',
      msTileImage: 'img/icons/mstile-144x144.png',
    },
    workboxOptions: {
      skipWaiting: true,
    },
  },
}
