// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import App from './App'
import api from './utils/api'
import Vuex from 'vuex'
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
Vue.use(ElementUI)

Vue.config.productionTip = false
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
