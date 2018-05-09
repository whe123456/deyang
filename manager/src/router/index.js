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
        // {
        //   path: '/class',
        //   component: resolve => require(['../components/page/ClassManagement.vue'], resolve),
        //   meta: {
        //     keepAlive: true // 需要缓存
        //   }
        // },
        // {
        //   path: '/change',
        //   component: resolve => require(['../components/page/ChangeClass.vue'], resolve),
        //   meta: {
        //     keepAlive: false // 需要缓存
        //   }
        // },
        {
          path: '/cuser',
          component: resolve => require(['../components/page/ClassUser.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/white',
          component: resolve => require(['../components/page/WhiteList.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/whiteadd',
          component: resolve => require(['../components/page/WhiteListAdd.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        },
        {
          path: '/userkq',
          component: resolve => require(['../components/page/GetKqJl.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        },
        {
          path: '/getqjjl',
          component: resolve => require(['../components/page/GetQjJl.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        },
        {
          path: '/force',
          component: resolve => require(['../components/page/ForceList.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/transaction',
          component: resolve => require(['../components/page/TransactionList.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/mangeruser',
          component: resolve => require(['../components/page/UserManage.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/manageruser',
          component: resolve => require(['../components/page/MangerUser.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        },
        {
          path: '/role',
          component: resolve => require(['../components/page/Role.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/managerrole',
          component: resolve => require(['../components/page/ManagerRole.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        },
        {
          path: '/menu',
          component: resolve => require(['../components/page/Menu.vue'], resolve),
          meta: {
            keepAlive: true // 需要缓存
          }
        },
        {
          path: '/managermenu',
          component: resolve => require(['../components/page/ManagerMenu.vue'], resolve),
          meta: {
            keepAlive: false // 需要缓存
          }
        // },
        // {
        //   path: '/grade',
        //   component: resolve => require(['../components/page/Grade.vue'], resolve),
        //   meta: {
        //     keepAlive: true // 需要缓存
        //   }
        // },
        // {
        //   path: '/grademenu',
        //   component: resolve => require(['../components/page/GradeMenu.vue'], resolve),
        //   meta: {
        //     keepAlive: false // 需要缓存
        //   }
        }
      ]
    },
    {
      path: '/login',
      component: resolve => require(['../components/page/Login.vue'], resolve)
    }
  ]
})
