<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 年级管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <span class="demonstration">年级名称</span>
          <el-input
            placeholder="请输入班级名称"
            v-model="formInline.BjName"
            class="sr_input"
            clearable>
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
          <el-button type="primary" @click="oncancle">取消查询</el-button>
        </el-form-item>
        <el-form-item class="right">
          <el-button type="primary" @click="AddClass">添加</el-button>
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
          prop="id"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="name"
          label="年级名称">
        </el-table-column>
        <el-table-column
          prop="coin"
          label="功能">
          <template slot-scope="scope">
            <el-button @click="ChangeClick(scope.row)" type="text" size="small">修改</el-button>
            <!--<el-button @click="delClick(scope.row)" type="text" size="small">删除</el-button>-->
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
        BjName: ''
      },
      loading: true,
      page: 5,
      total: 0,
      now_page: 1,
      js_id: 0,
      tableData: []
    }
  },
  methods: {
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.BjName = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    ChangeClick (e) {
      this.$router.push({path: '/grademenu', query: { id: e.id }})
    },
    AddClass () {
      this.$router.push({path: '/grademenu'})
    // },
    // delClick (e) {
    //   const usersName = sessionStorage.getItem('ms_username')
    //   if (usersName === null) {
    //     this.$router.push('/Login')
    //     return false
    //   }
    //   const url = localStorage.getItem('url')
    //   const that = this
    //   this.axios.get(url + 'api/api_get_grade_del.php', { username: usersName, grade: e.name }, function (res) {
    //     if (res.state === 'true') {
    //       that.$message('删除成功')
    //       getList(that.now_page, that)
    //     } else {
    //       that.$alert(res.msg, '提示', {
    //         confirmButtonText: '确定'
    //       })
    //     }
    //   })
    }
  },
  mounted () {
    this.$store.state.adminleftnavnum = this.$route.path.replace('/', '')
    const that = this
    this.js_id = sessionStorage.getItem('js_id')
    const usersName = sessionStorage.getItem('ms_username')
    if (usersName === null) {
      that.$router.push('/Login')
      return false
    }
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
  const BjName = that.formInline.BjName
  that.axios.get(url + 'api/api_get_grade_list.php', { username: usersName, page: page, grade: BjName }, function (res) {
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
