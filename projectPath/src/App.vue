<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'app'
}
</script>

<style lang="less">
@import '~vux/src/styles/reset.less';

body {
  background-color: #fbf9fe;
}
.td_group>div{margin-top: 0!important;}
.sp_btn{width: 90% !important;
    margin: 15px auto;}
</style>
