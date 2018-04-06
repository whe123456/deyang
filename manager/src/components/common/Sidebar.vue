<template>
  <div class="sidebar">
    <el-menu :default-active="navselected" @select="selectItems" class="el-menu-vertical-demo" theme="dark" unique-opened router
             background-color="#324157"
             text-color="#fff"
             active-text-color="#ffd04b">
      <template v-for="item in items">
        <template v-if="item.subs">
          <el-submenu :index="item.index" :key="item.id">
            <template slot="title"><i :class="item.icon"></i>{{ item.title }}</template>
            <el-menu-item v-for="(subItem,i) in item.subs" :key="i" :index="subItem.index">{{ subItem.title }}
            </el-menu-item>
          </el-submenu>
        </template>
        <template v-else>
          <el-menu-item :index="item.index" :key="item.id">
            <i :class="item.icon"></i>{{ item.title }}
          </el-menu-item>
        </template>
      </template>
    </el-menu>
  </div>
</template>

<script>
export default {
  data () {
    return {
      navtype: 'horizontal',
      navselected: '1',
      items: []
    }
  },
  methods: {
    getNavType () {
      this.navselected = this.$store.state.adminleftnavnum // store.state.adminleftnavnum里值变化的时候，设置navselected
    },
    selectItems (index) {
      this.$store.state.adminleftnavnum = index // 按钮选中之后设置当前的index为store里的值。
    }
  },
  watch: { // 监测store.state
    '$store.state.adminleftnavnum': 'getNavType'
  },
  mounted () {
    this.$store.state.adminleftnavnum = this.$route.path.replace('/', '')
    const usersName = localStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const that = this
    this.axios.get(url + 'api/api_get_sildebar.php', { username: usersName }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.items = res.list
      } else {
        that.$alert(res.msg, '提示', {
          confirmButtonText: '确定'
        })
      }
    })
  }
}
</script>

<style scoped>
  .sidebar {
    display: block;
    position: absolute;
    width: 250px;
    left: 0;
    top: 70px;
    bottom: 0;
    background: #324157;
  }
  .el-menu{height: 100%;}
</style>
