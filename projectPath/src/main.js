// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import FastClick from 'fastclick'
import App from './App'
import api from './utils/api'
import router from './router'
import Vuex from 'vuex'
Vue.use(require('vue-wechat-title'))
Vue.use(Vuex)
Vue.prototype.axios = api
const store = new Vuex.Store({
  state: {
    count: 0,
    adminleftnavnum: '1'
  },
  mutations: {
    increment (state) {
      state.count++
    }
  }
})

FastClick.attach(document.body)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app-box')
