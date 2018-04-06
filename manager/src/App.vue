<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
  * {
    margin: 0;
    padding: 0;
  }
#app, .wrapper, body, html {
  width: 100%;
  height: 100%;
  overflow: hidden;
}
  .el-submenu>.el-submenu__title,.el-menu-item {
    color: #bfcbd9;
  }
</style>
