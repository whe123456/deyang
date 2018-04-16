<template>
  <div class="content">
    <transition name="move" mode="out-in">
      <router-view></router-view>
    </transition>
    <tabbar>
      <tabbar-item link="/today" :selected="$route.path == '/today'">
        <img slot="icon" src="../../assets/banner/ic_tabbar_home.png">
        <img slot="icon-active" src="../../assets/banner/ic_tabbar_home_select.png">
        <span slot="label">今日考勤</span>
      </tabbar-item>
      <tabbar-item link="/list" :selected="$route.path == '/list'">
        <img slot="icon" src="../../assets/banner/ic_tabbar_notification.png">
        <img slot="icon-active" src="../../assets/banner/ic_tabbar_notification_select.png">
        <span slot="label">我的考勤</span>
      </tabbar-item>
      <tabbar-item link="/leave" :selected="$route.path == '/leave'">
        <img slot="icon" src="../../assets/banner/ic_tabbar_profile.png">
        <img slot="icon-active" src="../../assets/banner/ic_tabbar_profile_select.png">
        <span slot="label">请假申请</span>
      </tabbar-item>
      <tabbar-item link="/leavelist" :selected="$route.path == '/leavelist'">
        <img slot="icon" src="../../assets/banner/ic_tabbar_notification.png">
        <img slot="icon-active" src="../../assets/banner/ic_tabbar_notification_select.png">
        <span slot="label">请假记录</span>
      </tabbar-item>
    </tabbar>
  </div>
</template>

<script>
  import { Tabbar, TabbarItem } from 'vux'

  export default {
    components: {
      Tabbar,
      TabbarItem
    },
    mounted () {
      // localStorage.setItem('url', 'http://127.0.0.1:8180/')
      localStorage.setItem('wxid', '111')
      localStorage.setItem('url', 'http://192.168.0.188:8880/')
    }
  }
</script>
<style>
</style>
