<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 班级管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <span class="demonstration">班级名称</span>
          <el-input
            placeholder="请输入班级名称"
            v-model="formInline.BjName"
            class="sr_input"
            clearable>
          </el-input>
          <span class="demonstration">管理老师</span>
          <el-input
            placeholder="请输入管理老师"
            v-model="formInline.GlTeacher"
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
          prop="bj_mc"
          label="班级名称">
        </el-table-column>
        <el-table-column
          prop="js_bh"
          label="教室编号">
        </el-table-column>
        <el-table-column
          prop="xm"
          label="班级管理老师">
        </el-table-column>
        <el-table-column
          prop="coin"
          label="功能">
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
        BjName: '',
        GlTeacher: ''
      },
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
      this.formInline.BjName = ''
      this.formInline.GlTeacher = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    ChangeClick (e) {
      this.$router.push({path: '/change', query: { bm: e.bj_bm }})
    },
    AddClass () {
      this.$router.push({path: '/change'})
    },
    delClick (e) {
      const usersName = localStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const that = this
      this.axios.get(url + 'api/api_get_class_del.php', { username: usersName, BjBm: e.bj_bm }, function (res) {
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
  const BjName = that.formInline.BjName
  const GlTeacher = that.formInline.GlTeacher
  that.axios.get(url + 'api/api_get_class_list.php', { username: usersName, page: page, BjName: BjName, ClassTeach: GlTeacher }, function (res) {
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
</style>
