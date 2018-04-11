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
            <el-button type="primary" @click="onback">返回</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div>
        <el-table
          :data="tableData"
          stripe
          v-loading="loading"
          style="width: 100%">
          <el-table-column
            prop="sjhm"
            label="手机号">
          </el-table-column>
          <el-table-column
            prop="xm"
            label="姓名">
          </el-table-column>
          <el-table-column
            prop="cj_time"
            label="创建时间">
          </el-table-column>
          <el-table-column
            prop="sf_yz"
            label="是否验证">
            <template slot-scope="scope">
              <label v-if="scope.row.sf_yz===0">未验证</label>
              <label v-else>已验证</label>
            </template>
          </el-table-column>
          <el-table-column
            prop="yzm"
            label="验证码">
          </el-table-column>
          <el-table-column
            prop="yzsj"
            label="验证时间">
          </el-table-column>
          <el-table-column
            prop="xh"
            label="学号">
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
        xh: ''
      },
      loading: true,
      jz_loading: false,
      page: 5,
      total: 0,
      now_page: 1,
      bm: ''
    }
  },
  methods: {
    onSubmit () {
      getList(1, this)
    },
    onback () {
      history.back()
    },
    oncancle () {
      this.formInline.name = ''
      this.formInline.xh = ''
      getList(1, this)
    },
    on_upload () {
      this.jz_loading = true
    },
    upload_ok () {
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
      const usersName = localStorage.getItem('ms_username')
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
    this.bm = this.$route.query.bm
    getList(1, that)
  }
}
const getList = function (page, that) {
  const usersName = localStorage.getItem('ms_username')
  if (usersName === null) {
    that.$router.push('/Login')
    return false
  }
  that.loading = true
  const url = localStorage.getItem('url')
  const name = that.formInline.name
  const xh = that.formInline.xh
  const bm = that.bm
  that.axios.get(url + 'api/api_get_bmd.php', { username: usersName, page: page, name: name, xh: xh, bj_bm: bm }, function (res) {
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
