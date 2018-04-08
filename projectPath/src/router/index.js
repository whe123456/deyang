import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/today'
    },
    {
      path: '/today',
      component: resolve => require(['../components/TodayAttendance'], resolve),
      meta: { title: '今日考勤' }
    }
  ]
})
