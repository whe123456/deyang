<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 系统管理</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="教师编码">
        <el-input v-model="form.js_bm" class="width300" :disabled="sf_ds"></el-input>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="form.xm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="手机号">
        <el-input v-model="form.sjhm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="角色选择">
        <el-select v-model="form.classs" :value="form.classs" clearable filterable placeholder="请选择" class="width300">
          <el-option
            v-for="item in options"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="班级编码">
        <el-input v-model="form.bjbm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="班级名称">
        <el-input v-model="form.bj_mc" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="教室编号">
        <el-input v-model="form.js_bh" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="年级名称">
        <el-input v-model="form.grade" class="width300"></el-input>
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
        xm: '',
        sjhm: '',
        js_bm: '',
        bjbm: '',
        bj_mc: '',
        js_bh: '',
        grade: ''
      },
      sf_ds: true,
      loading: true,
      options: [],
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
      this.axios.get(url + 'api/api_get_teacher_add_change.php', { username: usersName, id: id, form: form }, function (res) {
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
      this.sf_ds = true
      this.ClassName = '教师信息修改'
    } else {
      this.ClassName = '新增教师'
      this.wb_text = '立即创建'
      this.sf_ds = false
    }
    const usersName = sessionStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const id = this.id
    const that = this
    this.axios.get(url + 'api/api_get_teacher_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.options = res.list
        if (id !== '') {
          that.form.classs = res.bmd.js_id
          that.form.xm = res.bmd.xm
          that.form.sjhm = res.bmd.sjhm
          that.form.js_bm = res.bmd.js_bm
          that.form.bjbm = res.bmd.bjbm
          that.form.bj_mc = res.bmd.bj_mc
          that.form.js_bh = res.bmd.js_bh
          that.form.grade = res.bmd.grade
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
