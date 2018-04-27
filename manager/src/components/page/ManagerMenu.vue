<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 菜单管理</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="名称">
        <el-input v-model="form.name" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="父级选择">
        <el-select v-model="form.fj" placeholder="请选择">
          <el-option
            v-for="item in options"
            :key="item.index"
            :label="item.label"
            :value="item.index">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="排序设置">
        <el-input v-model="form.order" class="width300"></el-input>越高排序越靠前
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
      options: [],
      form: {
        name: '',
        fj: '',
        order: ''
      },
      loading: true,
      ClassName: '',
      id: '',
      wb_text: '立即创建'
    }
  },
  methods: {
    onSubmit () {
      const usersName = sessionStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const id = this.id
      const form = this.form
      const that = this
      this.axios.get(url + 'api/api_get_menu_add_change.php', { username: usersName, id: id, form: form }, function (res) {
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
      this.ClassName = '菜单信息修改'
    }
    const usersName = sessionStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const id = this.id
    const that = this
    this.axios.get(url + 'api/api_get_menu_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.options = res.list
        if (id !== '') {
          that.form.name = res.bmd.title
          that.form.fj = res.bmd.parent
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
