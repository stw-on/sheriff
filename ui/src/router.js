import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {path: '/', name: 'index', component: require('./pages/index-page').default},
  {path: '/register', name: 'register', component: require('./pages/register-page').default},
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})

export default router
