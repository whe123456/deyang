<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 角色管理</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="名称">
        <el-input v-model="form.name" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="描述">
        <el-input v-model="form.ms" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="权限分配">
      <el-transfer v-model="form.tranfer" :data="data" :titles="['不可访问', '可访问']"></el-transfer>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">{{wb_text}}</el-button>
        <el-button type="primary" @click="oncancle">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      data: [],
      form: {
        name: '',
        tranfer: [],
        ms: ''
      },
      loading: true,
      ClassName: '',
      id: '',
      wb_text: '立即创建'
    }
  },
  methods: {
    onSubmit () {
      const usersName = localStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const id = this.id
      const form = this.form
      const that = this
      this.axios.get(url + 'api/api_get_role_add_change.php', { username: usersName, id: id, form: form }, function (res) {
        if (res.state === 'true') {
          history.back()
        } else {
          that.$alert(res.msg, '提示', {
            confirmButtonText: '确定'
          })
        }
      })
    },
    oncancle () {
      history.back()
    }
  },
  mounted () {
    if (this.$route.query.id !== undefined) {
      this.id = this.$route.query.id
      this.wb_text = '立即修改'
      this.ClassName = '角色信息修改'
    } else {
      this.ClassName = '新增角色'
      this.wb_text = '立即创建'
    }
    const usersName = localStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const id = this.id
    const that = this
    this.axios.get(url + 'api/api_get_role_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.data = res.list
        if (id !== '') {
          that.form.name = res.bmd.name
          that.form.tranfer = res.bmd.qx_list
          that.form.ms = res.bmd.ms
        }
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
  .el-breadcrumb{font-size: 30px;    margin-bottom: 22px;}
  .el-pagination{text-align: center;}
  .width300{width: 300px;}
</style>
