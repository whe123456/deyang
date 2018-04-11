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
      component: resolve => require(['../components/common/Home.vue'], resolve),
      children: [
        {
          path: '/today',
          component: resolve => require(['../components/TodayAttendance'], resolve),
          meta: { title: '今日考勤' }
        },
        {
          path: '/list',
          component: resolve => require(['../components/AttendanceList'], resolve),
          meta: { title: '我的考勤' }
        },
        {
          path: '/leave',
          component: resolve => require(['../components/LeaveApply'], resolve),
          meta: { title: '请假申请' }
        }
      ]
    }
  ]
})
