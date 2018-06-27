<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 班主任列表</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <div>
        <el-form :inline="true" :model="formInline" class="demo-form-inline">
          <el-form-item>
            <span class="demonstration">班级编号</span>
            <el-input
              placeholder="请输入班级编号"
              v-model="formInline.bjbm"
              class="sr_input"
              clearable>
            </el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">查询</el-button>
            <el-button type="primary" @click="oncancle">取消查询</el-button>
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
            fixed
            prop="js_bm"
            label="教师编码">
            <template slot-scope="scope">
              <span v-if="scope.row.js_bm===''">暂无</span>
              <span v-else>{{scope.row.js_bm}}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="xm"
            label="姓名">
            <template slot-scope="scope">
              <span v-if="scope.row.xm===''">暂无</span>
              <span v-else>{{scope.row.xm}}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="bjbm"
            label="班级编码">
          </el-table-column>
          <el-table-column
            prop="bj_mc"
            label="班级名称">
          </el-table-column>
          <el-table-column
            prop="coin"
            fixed="right"
            label="操作">
            <template slot-scope="scope">
              <el-button @click="ChangeClick(scope.row)" type="text" size="small">修改</el-button>
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
      formInline: {
        bjbm: ''
      },
      tableData: [],
      loading: true,
      page: 5,
      total: 0,
      now_page: 1
    }
  },
  methods: {
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.bjbm = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    ChangeClick (e) {
      this.$router.push({path: '/teacheradd', query: { bm: e.bjbm, js: e.js_bm }})
    }
  },
  mounted () {
    this.$store.state.adminleftnavnum = this.$route.path.replace('/', '')
    const that = this
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
  const bjbm = that.formInline.bjbm
  that.axios.get(url + 'api/api_get_bzr.php', { username: usersName, page: page, bj_bm: bjbm }, function (res) {
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
