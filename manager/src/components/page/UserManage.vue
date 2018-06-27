<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 用户管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <label>姓名查询</label>
          <el-input v-model="formInline.xm" placeholder="请输入姓名查询"></el-input>
          <label>编号查询</label>
          <el-input v-model="formInline.bh" placeholder="请输入编号查询"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
          <el-button type="primary" @click="oncancle">取消查询</el-button>
        </el-form-item>
        <el-form-item class="right">
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
          <el-button type="primary" @click="onExcel">导出</el-button>
          <el-button type="primary" @click="onAdd">新增</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div v-loading="loading">
      <el-table
        :data="tableData"
        stripe
        style="width: 100%">
        <el-table-column
          prop="id"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="js_bm"
          label="教师编号">
        </el-table-column>
        <el-table-column
          prop="xm"
          label="姓名">
        </el-table-column>
        <el-table-column
          prop="sjhm"
          label="手机号码">
        </el-table-column>
        <el-table-column
          prop="name"
          label="角色">
        </el-table-column>
        <el-table-column
          prop="sf_zc"
          label="是否绑定">
          <template slot-scope="scope">
            <span v-if="scope.row.sf_zc === 1">是</span>
            <span v-else>否</span>
          </template>
        </el-table-column>
        <!--<el-table-column-->
          <!--prop="ewm_url"-->
          <!--label="二维码">-->
        <!--</el-table-column>-->
        <el-table-column
          prop="coin"
          label="操作">
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
</template>

<script>
export default {
  data () {
    return {
      formInline: {
        xm: '',
        bh: ''
      },
      loading: true,
      tableData: [],
      jz_loading: false,
      fileList: [],
      address: '',
      page: 5,
      total: 0,
      UserId: '',
      thatUserTel: '',
      UserForce: ''
    }
  },
  methods: {
    on_upload () {
      this.jz_loading = true
    },
    upload_ok () {
      this.jz_loading = false
      this.fileList = []
      getList(1, this)
    },
    downClass () {
      const url = localStorage.getItem('url')
      window.open(url + 'api/demo/teacher_demo_list.xlsx')
    },
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.xm = ''
      this.formInline.bh = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    delClick (e) {
      const id = e.js_bm
      this.$confirm('此操作将永久删除该教师, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        const usersName = sessionStorage.getItem('ms_username')
        if (usersName === null) {
          this.$router.push('/Login')
          return false
        }
        const url = localStorage.getItem('url')
        const that = this
        that.axios.get(url + 'api/api_get_user_del.php', { username: usersName, id: id }, function (res) {
          if (res.state === 'true') {
            getList(1, that)
          } else {
            that.$alert(res.msg, '提示', {
              confirmButtonText: '确定'
            })
          }
        })
      })
    },
    ChangeClick (e) {
      this.$router.push({path: '/manageruser', query: { id: e.id }})
    },
    onAdd () {
      this.$router.push({path: '/manageruser'})
    },
    onExcel () {
      const usersName = sessionStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const Bjmc = this.formInline.bj_mc
      const KqLx = this.formInline.kq_lx
      const Kqsj = this.formInline.kq_sj
      const xm = this.formInline.xm
      window.open(url + 'api/export/export_user.php?username=' + usersName + '&bj_mc=' + Bjmc + '&sf_ty=' + KqLx + '&kq_sj=' + Kqsj + '&xm=' + xm)
    }
  },
  mounted () {
    this.$store.state.adminleftnavnum = this.$route.path.replace('/', '')
    const that = this
    const url = localStorage.getItem('url')
    this.address = url + 'api/export/teacher_list_import.php'
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
  const xm = that.formInline.xm
  const bh = that.formInline.bh
  that.axios.get(url + 'api/api_get_user_manager.php', { username: usersName, page: page, xm: xm, bh: bh }, function (res) {
    that.loading = false
    if (res.state === 'true') {
      that.page = res.page
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
  .right{float:right;}
  .size20{font-size: 20px;}
  .el-input {
    width: 200px;
  }
  .upload-demo{display: inline-block;}
</style>
