<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 白名单</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="班级选择">
        <el-select v-model="form.classs" :value="form.classs" clearable filterable placeholder="请选择" class="width300">
          <el-option
            v-for="item in options"
            :key="item.bj_bm"
            :label="item.bj_mc"
            :value="item.bj_bm">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="学号">
        <el-input v-model="form.xh" class="width300" :disabled="sf_ds"></el-input>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="form.xm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="手机号">
        <el-input v-model="form.sjhm" class="width300"></el-input>
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
      form: {
        classs: '',
        xh: '',
        xm: '',
        sjhm: ''
      },
      sf_ds: true,
      loading: true,
      options: [],
      ClassName: '',
      bm: '',
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
      const id = this.bm
      const form = this.form
      const that = this
      this.axios.get(url + 'api/api_get_white_add_change.php', { username: usersName, id: id, form: form }, function (res) {
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
    if (this.$route.query.bm !== undefined) {
      this.bm = this.$route.query.bm
      this.wb_text = '立即修改'
      this.sf_ds = true
      this.ClassName = '白名单修改'
    } else {
      this.ClassName = '新增白名单'
      this.wb_text = '立即创建'
      this.sf_ds = false
    }
    const usersName = localStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const id = this.bm
    const that = this
    this.axios.get(url + 'api/api_get_class_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.options = res.list
        if (id !== '') {
          that.form.classs = res.bmd.bjbm
          that.form.xh = res.bmd.xh
          that.form.xm = res.bmd.xm
          that.form.sjhm = res.bmd.sjhm
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
