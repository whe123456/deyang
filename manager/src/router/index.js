import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/class',
      component: resolve => require(['../components/common/Home.vue'], resolve),
      children: [
        {
          path: '/class',
          component: resolve => require(['../components/page/ClassManagement.vue'], resolve)
        },
        {
          path: '/change',
          component: resolve => require(['../components/page/ChangeClass.vue'], resolve)
        },
        {
          path: '/cuser',
          component: resolve => require(['../components/page/ClassUser.vue'], resolve)
        },
        {
          path: '/white',
          component: resolve => require(['../components/page/WhiteList.vue'], resolve)
        },
        {
          path: '/whiteadd',
          component: resolve => require(['../components/page/WhiteListAdd.vue'], resolve)
        },
        {
          path: '/userkq',
          component: resolve => require(['../components/page/GetKqJl.vue'], resolve)
        },
        {
          path: '/getqjjl',
          component: resolve => require(['../components/page/GetQjJl.vue'], resolve)
        },
        {
          path: '/force',
          component: resolve => require(['../components/page/ForceList.vue'], resolve)
        },
        {
          path: '/transaction',
          component: resolve => require(['../components/page/TransactionList.vue'], resolve)
        },
        {
          path: '/mangeruser',
          component: resolve => require(['../components/page/UserManage.vue'], resolve)
        },
        {
          path: '/manageruser',
          component: resolve => require(['../components/page/MangerUser.vue'], resolve)
        },
        {
          path: '/role',
          component: resolve => require(['../components/page/Role.vue'], resolve)
        },
        {
          path: '/managerrole',
          component: resolve => require(['../components/page/ManagerRole.vue'], resolve)
        },
        {
          path: '/menu',
          component: resolve => require(['../components/page/Menu.vue'], resolve)
        },
        {
          path: '/managermenu',
          component: resolve => require(['../components/page/ManagerMenu.vue'], resolve)
        }
      ]
    },
    {
      path: '/login',
      component: resolve => require(['../components/page/Login.vue'], resolve)
    }
  ]
})
