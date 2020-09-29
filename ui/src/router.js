import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {path: '/', name: 'index', component: require('./pages/index-page').default},
  {path: '/register', name: 'register', component: require('./pages/register-page').default},
  {
    path: '/admin', component: require('./pages/admin-page').default,
    children: [
      {path: '', name: 'admin/index', component: require('./pages/admin/index-page').default},
      {path: 'location/:id?', name: 'admin/location', component: require('./pages/admin/location-page').default},
    ],
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})

export default router
