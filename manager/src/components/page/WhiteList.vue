<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 白名单</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div  v-loading="jz_loading">
      <div>
        <el-form :inline="true" :model="formInline" class="demo-form-inline">
          <el-form-item>
            <span class="demonstration">姓名</span>
            <el-input
              placeholder="请输入姓名"
              v-model="formInline.name"
              class="sr_input"
              clearable>
            </el-input>
            <span class="demonstration">手机号码查询</span>
            <el-input
              placeholder="请输入手机号码"
              v-model="formInline.sjhm"
              class="sr_input"
              clearable>
            </el-input>
            <span class="demonstration">学号</span>
            <el-input
              placeholder="请输入学号"
              v-model="formInline.xh"
              class="sr_input"
              clearable>
            </el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">查询</el-button>
            <el-button type="primary" @click="oncancle">取消查询</el-button>
          </el-form-item>
          <el-form-item class="right">
            <el-upload
              class="upload-demo"
              :action="address2"
              accept=".zip"
              :on-progress="zip_upload"
              :on-success="upload_zip"
              :show-file-list="false"
              :file-list="zip_fileList">
              <el-popover
                placement="top-start"
                title="提示"
                width="200"
                trigger="hover"
                content="请将所需上传的图片文件放至所需上传文件夹内再行打包上传">
              <el-button type="primary" slot="reference">导入zip图片压缩包</el-button>
              </el-popover>
            </el-upload>
            <el-upload
              class="upload-demo"
              :action="address"
              :limit="1"
              accept=".xls,.xlsx"
              :on-progress="on_upload"
              :on-success="upload_ok"
              :show-file-list="false"
              :file-list="fileList">
              <el-button type="primary">导入文件</el-button>
            </el-upload>
            <el-button type="primary" @click="downClass">示例文件下载</el-button>
            <el-button type="primary" @click="AddClass">添加</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div>
        <el-table
          :data="tableData"
          stripe
          border
          v-loading="loading"
          style="width: 100%">
          <el-table-column
            fixed
            prop="sjhm"
            label="手机号"
            width="120">
          </el-table-column>
          <el-table-column
            prop="xm"
            label="姓名"
            width="120">
          </el-table-column>
          <el-table-column
            prop="cj_time"
            label="创建时间"
            width="220">
          </el-table-column>
          <el-table-column
            prop="sf_yz"
            label="是否验证"
            width="100">
            <template slot-scope="scope">
              <label v-if="scope.row.sf_yz===0">未验证</label>
              <label v-else>已验证</label>
            </template>
          </el-table-column>
          <el-table-column
            prop="sex"
            label="性别"
            width="100">
          </el-table-column>
          <el-table-column
            prop="photo"
            label="照片"
            width="100">
            <template slot-scope="scope" v-if="scope.row.photo!==null&&scope.row.photo!==''">
              <div style="width: 80px;height: 80px;"><img :src="scope.row.photo" style="width: 100%;height: 100%;"/></div>
            </template>
          </el-table-column>
          <el-table-column
            prop="yzm"
            label="验证码"
            width="100">
          </el-table-column>
          <el-table-column
            prop="yzsj"
            label="验证时间"
            width="200">
          </el-table-column>
          <el-table-column
            prop="bjbm"
            label="班级编码"
            width="100">
          </el-table-column>
          <el-table-column
            prop="xh"
            label="学号"
            width="200">
          </el-table-column>
          <el-table-column
            prop="coin"
            fixed="right"
            label="功能"
            width="200">
            <template slot-scope="scope">
              <el-button @click="ChangeClick(scope.row)" type="text" size="small">修改</el-button>
              <el-button @click="delClick(scope.row)" type="text" size="small">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          @current-change="handleCurrentChange"
          :page-size="page"
          layout="total, prev, pager, next"
          :total="total">
        </el-pagination>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      fileList: [],
      address: '',
      formInline: {
        name: '',
        xh: '',
        sjhm: ''
      },
      tableData: [],
      loading: true,
      jz_loading: false,
      page: 5,
      total: 0,
      now_page: 1,
      zip_fileList: [],
      address2: ''
    }
  },
  methods: {
    zip_upload () {
      this.jz_loading = true
    },
    upload_zip (e) {
      console.log(e)
      this.jz_loading = false
      this.fileList = []
      getList(1, this)
    },
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.name = ''
      this.formInline.xh = ''
      getList(1, this)
    },
    on_upload () {
      this.jz_loading = true
    },
    upload_ok (e) {
      if (e.state === 'false') {
        this.$alert(e.msg, '提示', {
          confirmButtonText: '确定'
        })
      }
      this.jz_loading = false
      this.fileList = []
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    ChangeClick (e) {
      this.$router.push({path: '/whiteadd', query: { bm: e.id }})
    },
    AddClass () {
      this.$router.push({path: '/whiteadd'})
    },
    downClass () {
      const url = localStorage.getItem('url')
      window.open(url + 'api/demo/white_demo_list.xlsx')
    },
    delClick (e) {
      const usersName = sessionStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const that = this
      this.axios.get(url + 'api/api_get_bmd_del.php', { username: usersName, id: e.id }, function (res) {
        if (res.state === 'true') {
          that.$message('删除成功')
          getList(that.now_page, that)
        } else {
          that.$alert(res.msg, '提示', {
            confirmButtonText: '确定'
          })
        }
      })
    }
  },
  mounted () {
    this.$store.state.adminleftnavnum = this.$route.path.replace('/', '')
    const that = this
    const url = localStorage.getItem('url')
    this.address = url + 'api/export/whitelist_import.php'
    this.address2 = url + 'api/export/white_zip_import.php'
    getList(1, that)
  }
}
const getList = function (page, that) {
  const usersName = sessionStorage.getItem('ms_username')
  if (usersName === null) {
    that.$router.push('/Login')
    return false
  }
  that.loading = true
  const url = localStorage.getItem('url')
  const name = that.formInline.name
  const xh = that.formInline.xh
  const sjhm = that.formInline.sjhm
  that.axios.get(url + 'api/api_get_bmd.php', { username: usersName, page: page, name: name, xh: xh, sjhm: sjhm }, function (res) {
    that.now_page = page
    that.loading = false
    if (res.state === 'true') {
      that.page = res.page_size
      that.total = res.count
      if (res.list !== false) {
        that.tableData = res.list
      } else {
        that.tableData = []
      }
    } else {
      that.$alert(res.msg, '提示', {
        confirmButtonText: '确定'
      })
    }
  })
}
</script>

<style scoped>
  .el-breadcrumb{font-size: 30px;    margin-bottom: 22px;}
  .el-pagination{text-align: center;}
  .sr_input{width: 200px;}
  .right{float:right;}
  .upload-demo{display: inline-block;}
</style>
