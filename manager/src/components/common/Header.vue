<template>
  <div class="header">
    <div class="logo">后台管理系统</div>
    <div class="user-info">
      <span class="el-dropdown-link">
          {{username}}
      </span>
      <span class="el-dropdown-link" @click="change_pass">
          密码修改
      </span>
      <span class="el-dropdown-link" @click="loginout">
          退出
      </span>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      name: ''
    }
  },
  computed: {
    username () {
      let usersName = sessionStorage.getItem('ms_username')
      if (usersName) {
        return usersName
      } else {
        return this.name
      }
    }
  },
  methods: {
    loginout () {
      sessionStorage.removeItem('ms_username')
      this.$router.push('/login')
    },
    change_pass () {
      this.$router.push('/change_pass')
    }
  }
}
</script>
<style scoped>
  .header {
    position: relative;
    box-sizing: border-box;
    width: 100%;
    height: 70px;
    font-size: 22px;
    line-height: 70px;
    color: #fff;
    background-color: #242f42;
  }

  .header .logo {
    float: left;
    width: 250px;
    text-align: center;
  }

  .user-info {
    float: right;
    padding-right: 50px;
    font-size: 16px;
    color: #fff;
  }

  .user-info .el-dropdown-link {
    position: relative;
    display: inline-block;
    padding-left: 50px;
    color: #fff;
    cursor: pointer;
    vertical-align: middle;
  }

  .user-info .user-logo {
    position: absolute;
    left: 0;
    top: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }

  .el-dropdown-menu__item {
    text-align: center;
  }
</style>
