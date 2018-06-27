<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 白名单</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <el-form-item label="班级编码">
        <el-input v-model="form.bjbm" class="width300" disabled></el-input>
      </el-form-item>
      <el-form-item label="班级名称">
        <el-input v-model="form.bj_mc" class="width300" disabled></el-input>
      </el-form-item>
      <el-form-item label="教师选择">
        <el-select v-model="form.js_bm" placeholder="请选择">
          <el-option
            v-for="item in teacher"
            :key="item.js_bm"
            :label="item.xm"
            :value="item.js_bm">
          </el-option>
        </el-select>
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
        bjbm: '',
        bj_mc: '',
        js_bm: ''
      },
      teacher: [],
      loading: true,
      // options: [],
      ClassName: '',
      bm: '',
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
      const bjbm = this.form.bjbm
      const jsbm = this.form.js_bm
      const that = this
      this.axios.get(url + 'api/api_get_bzr_add_change.php', { username: usersName, bjbm: bjbm, js_bm: jsbm }, function (res) {
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
      this.ClassName = '白名单修改'
    } else {
      this.ClassName = '新增白名单'
      this.wb_text = '立即创建'
    }
    if (this.$route.query.js !== undefined) {
      this.form.js_bm = this.$route.query.js
    }
    const usersName = sessionStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    const id = this.bm
    const that = this
    this.axios.get(url + 'api/api_get_bzr_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        that.teacher = res.list
        if (id !== '') {
          that.form.bjbm = res.bmd.bjbm
          that.form.bj_mc = res.bmd.bj_mc
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
  .el-upload-list .el-upload-list--picture-card{display: none!important;}
  .el-upload-list>li:last-child{display: block;}
</style>
