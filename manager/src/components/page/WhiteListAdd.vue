<template>
  <div class="crumbs">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/white' }"><i class="el-icon-tickets"></i> 白名单</el-breadcrumb-item>
      <el-breadcrumb-item> {{ClassName}}</el-breadcrumb-item>
    </el-breadcrumb>
    <el-form ref="form" :model="form" label-width="80px" v-loading="loading">
      <!--<el-form-item label="班级选择">-->
        <!--<el-select v-model="form.classs" :value="form.classs" clearable filterable placeholder="请选择" class="width300">-->
          <!--<el-option-->
            <!--v-for="item in options"-->
            <!--:key="item.bj_bm"-->
            <!--:label="item.bj_mc"-->
            <!--:value="item.bj_bm">-->
          <!--</el-option>-->
        <!--</el-select>-->
      <!--</el-form-item>-->
      <el-form-item label="班级编码">
        <el-input v-model="form.bjbm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="班级名称">
        <el-input v-model="form.bj_mc" class="width300"></el-input>
      </el-form-item>
      <!--<el-form-item label="教室编号">-->
        <!--<el-input v-model="form.js_bh" class="width300"></el-input>-->
      <!--</el-form-item>-->
      <!--<el-form-item label="年级名称">-->
        <!--<el-input v-model="form.grade" class="width300"></el-input>-->
      <!--</el-form-item>-->
      <el-form-item label="学号">
        <el-input v-model="form.xh" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="form.xm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="手机号">
        <el-input v-model="form.sjhm" class="width300"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-select v-model="form.sex" placeholder="请选择">
          <el-option
            v-for="item in options_sex"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="照片上传">
        <el-upload
          :action="address"
          list-type="picture-card"
          :file-list="fileList2"
          :on-preview="handlePictureCardPreview"
          :on-success="upload_success"
          :before-upload="beforeAvatarUpload">
          <i class="el-icon-plus"></i>
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
          <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
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
        // js_bh: '',
        // grade: '',
        xh: '',
        xm: '',
        sjhm: '',
        sex: '男'
      },
      options_sex: [{
        value: '男',
        label: '男'
      }, {
        value: '女',
        label: '女'
      }],
      loading: true,
      // options: [],
      ClassName: '',
      bm: '',
      wb_text: '立即创建',
      dialogImageUrl: '',
      dialogVisible: false,
      address: '',
      fileList2: []
    }
  },
  methods: {
    handlePictureCardPreview (file) {
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    upload_success (e) {
      if (e.state === 'true') {
        this.fileList2 = [{url: e.url}]
      }
    },
    beforeAvatarUpload (file) {
      const isJPG = file.type === 'image/jpeg'
      const isLt2M = file.size / 1024 / 1024 < 2

      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!')
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!')
      }
      return isJPG && isLt2M
    },
    onSubmit () {
      const usersName = sessionStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const id = this.bm
      const form = this.form
      const that = this
      this.axios.get(url + 'api/api_get_white_add_change.php', { username: usersName, id: id, form: form, url: this.fileList2 }, function (res) {
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
    const usersName = sessionStorage.getItem('ms_username')
    if (usersName === null) {
      this.$router.push('/Login')
      return false
    }
    const url = localStorage.getItem('url')
    this.address = url + 'api/export/upload_white_photo.php'
    const id = this.bm
    const that = this
    this.axios.get(url + 'api/api_get_class_change.php', { username: usersName, id: id }, function (res) {
      that.loading = false
      if (res.state === 'true') {
        // that.options = res.list
        if (id !== '') {
          console.log(res.bmd.photo)
          if (res.bmd.photo !== '' && res.bmd.photo !== null) {
            that.fileList2 = [{url: res.bmd.photo}]
          }
          that.form.bjbm = res.bmd.bjbm
          that.form.sex = res.bmd.sex
          that.form.bj_mc = res.bmd.bj_mc
          // that.form.js_bh = res.bmd.js_bh
          // that.form.grade = res.bmd.grade
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
  .el-upload-list .el-upload-list--picture-card{display: none!important;}
  .el-upload-list>li:last-child{display: block;}
</style>
