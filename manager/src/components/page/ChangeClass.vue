<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/class' }"><i class="el-icon-tickets"></i> 班级管理</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="班级编码">
        <el-input v-model="form.BjCode" class="width300" :disabled="sf_ds"></el-input>
      </el-form-item>
      <el-form-item label="班级名称">
        <el-input v-model="form.bj_mc" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="教室编号">
        <el-input v-model="form.js_bh" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="管理老师">
        <el-select v-model="form.gl_teacher" :value="form.gl_teacher" clearable filterable placeholder="请选择" class="width300">
          <el-option
            v-for="item in options"
            :key="item.js_bm"
            :label="item.xm"
            :value="item.js_bm">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="备注">
        <el-input type="textarea" v-model="form.desc" class="width300" autosize></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">立即创建</el-button>
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
        BjCode: '',
        bj_mc: '',
        js_bh: '',
        desc: '',
        gl_teacher: ''
      },
      sf_ds: true,
      loading: true,
      options: [],
      ClassName: '',
      bm: ''
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
      const BjBm = this.bm
      const form = this.form
      const that = this
      this.axios.get(url + 'api/api_get_class_add_change.php', { username: usersName, BjBm: BjBm, form: form }, function (res) {
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
      this.ClassName = '班级修改'
      this.sf_ds = true
    } else {
      this.ClassName = '新增班级'
      this.sf_ds = false
    }
    const usersName = localStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const BjBm = this.bm
    const that = this
    this.axios.get(url + 'api/api_get_class_change.php', { username: usersName, BjBm: BjBm }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.options = res.list
        if (BjBm !== '') {
          that.form.BjCode = res.bm.bj_bm
          that.form.bj_mc = res.bm.bj_mc
          that.form.js_bh = res.bm.js_bh
          that.form.desc = res.bm.bz
          that.form.gl_teacher = res.bm.js_bm
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
