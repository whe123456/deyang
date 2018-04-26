// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import FastClick from 'fastclick'
import App from './App'
import api from './utils/api'
import router from './router'
import Calendar from 'vue-calendar-component'
import { AlertPlugin, LoadingPlugin } from 'vux'
Vue.use(AlertPlugin)
Vue.use(LoadingPlugin)
Vue.use(require('vue-wechat-title'))
Vue.use(Calendar)
Vue.prototype.axios = api

FastClick.attach(document.body)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount('#app-box')
