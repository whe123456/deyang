import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/vux'
    },
    {
      path: '/vux',
      name: 'HelloFromVux',
      component: resolve => require(['../components/HelloFromVux'], resolve)
    }
  ]
})
