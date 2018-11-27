<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/change_pass' }"><i class="el-icon-tickets"></i> 密码修改</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form :model="ruleForm" status-icon :rules="rules2" ref="ruleForm" label-width="100px">
      <el-form-item label="原密码" prop="oldPass">
        <el-input v-model="ruleForm.oldPass" type="password" class="width300" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="密码" prop="pass">
        <el-input v-model="ruleForm.pass" type="password" class="width300" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="确认密码" prop="checkPass">
        <el-input type="password" v-model="ruleForm.checkPass" class="width300" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm('ruleForm')">{{wb_text}}</el-button>
        <el-button @click="resetForm('ruleForm')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    var checkoldPass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'))
      } else {
        callback()
      }
    }
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'))
      } else if (value === this.ruleForm.oldPass) {
        callback(new Error('新旧密码一致!'))
      } else {
        if (this.ruleForm.checkPass !== '') {
          this.$refs.ruleForm.validateField('checkPass')
        }
        callback()
      }
    }
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'))
      } else if (value !== this.ruleForm.pass) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      ruleForm: {
        pass: '',
        checkPass: '',
        oldPass: ''
      },
      rules2: {
        pass: [
          { validator: validatePass, trigger: 'blur' }
        ],
        checkPass: [
          { validator: validatePass2, trigger: 'blur' }
        ],
        oldPass: [
          { validator: checkoldPass, trigger: 'blur' }
        ]
      },
      teacher: [],
      ClassName: '',
      bm: '',
      wb_text: '立即修改'
    }
  },
  methods: {
    submitForm (formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          const usersName = sessionStorage.getItem('ms_username')
          if (usersName === null) {
            this.$router.push('/Login')
            return false
          }
          const that = this
          const url = localStorage.getItem('url')
          const pass = that.ruleForm.pass
          const checkPass = that.ruleForm.checkPass
          const oldPass = that.ruleForm.oldPass
          that.axios.get(url + 'api/change_pass.php', { username: usersName, pass: pass, checkPass: checkPass, oldPass: oldPass }, function (res) {
            if (res.state === 'true') {
              sessionStorage.removeItem('ms_username')
              that.$router.push('/login')
            } else {
              that.$alert(res.msg, '提示', {
                confirmButtonText: '确定'
              })
            }
          })
        } else {
          return false
        }
      })
    },
    resetForm (formName) {
      this.$refs[formName].resetFields()
    }
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
