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
          meta: { title: '今日考勤', keepAlive: true }
        },
        {
          path: '/list',
          component: resolve => require(['../components/AttendanceList'], resolve),
          meta: { title: '我的考勤', keepAlive: true }
        },
        {
          path: '/leave',
          component: resolve => require(['../components/LeaveApply'], resolve),
          meta: { title: '请假申请', keepAlive: true }
        },
        {
          path: '/leavelist',
          component: resolve => require(['../components/LeaveList'], resolve),
          meta: { title: '申请列表', keepAlive: true }
        },
        {
          path: '/leaveinfo',
          component: resolve => require(['../components/LeaveInfo'], resolve),
          meta: { title: '请假详情', keepAlive: false }
        },
        {
          path: '/leaveok',
          component: resolve => require(['../components/LeaveOk'], resolve),
          meta: { title: '请假成功', keepAlive: false }
        }
      ]
    },
    {
      path: '/stu',
      component: resolve => require(['../components/login/student.vue'], resolve),
      meta: { title: '学生绑定', keepAlive: false }
    },
    {
      path: '/teacher',
      component: resolve => require(['../components/login/teacher.vue'], resolve),
      meta: { title: '教师绑定', keepAlive: false }
    },
    {
      path: '/teachlist',
      component: resolve => require(['../components/teacher/LeaveList.vue'], resolve),
      meta: { title: '申请列表', keepAlive: false }
    },
    {
      path: '/leavesp',
      component: resolve => require(['../components/teacher/LeaveSp.vue'], resolve),
      meta: { title: '请假详情', keepAlive: false }
    },
    {
      path: '/leaveteachinfo',
      component: resolve => require(['../components/LeaveInfo.vue'], resolve),
      meta: { title: '请假详情', keepAlive: false }
    }
  ]
})
