<template>
  <div class="content">
    <transition name="move" mode="out-in" >
      <router-view :style="{height: sheight}" style="overflow: scroll;"></router-view>
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
  import { Tabbar, TabbarItem, ViewBox } from 'vux'

  export default {
    components: {
      Tabbar,
      TabbarItem,
      ViewBox
    },
    data () {
      return {
        sheight: ''
      }
    },
    created () {
      const url = this.$route.query.url
      if (url !== undefined) {
        localStorage.setItem('url', url)
      } else {
        localStorage.setItem('url', 'http://xs.17189.net/')
      }
      const wxid = this.$route.query.wxid
      if (wxid !== undefined) {
        localStorage.setItem('wxid', wxid)
      }
      this.sheight = document.documentElement.clientHeight - 55 + 'px'
      // localStorage.setItem('url', 'http://192.168.0.105:8180/')
      // localStorage.setItem('wxid', '111')
      // localStorage.setItem('url', 'http://192.168.0.188:8880/')
    }
  }
</script>
<style>
  html, body {
    height: 100%;
    width: 100%;
    overflow-x: hidden;
  }
</style>
