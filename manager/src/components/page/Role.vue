<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 角色管理</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <label>名称查询</label>
          <el-input v-model="formInline.xm" placeholder="请输入名称查询"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
          <el-button type="primary" @click="oncancle">取消查询</el-button>
        </el-form-item>
        <el-form-item class="right">
          <el-button type="primary" @click="onAdd">新增</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div>
      <el-table
        :data="tableData"
        stripe
        style="width: 100%">
        <el-table-column
          prop="id"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="name"
          label="名称">
        </el-table-column>
        <el-table-column
          prop="ms"
          label="角色描述">
        </el-table-column>
        <el-table-column
          prop="create_ts"
          label="创建时间">
        </el-table-column>
        <el-table-column
          prop="coin"
          label="操作">
          <template slot-scope="scope">
            <div v-if="scope.row.id !== 1 && scope.row.id !== 4">
              <el-button @click="ChangeClick(scope.row)" type="text" size="small">修改</el-button>
              <el-button @click="delClick(scope.row)" type="text" size="small">删除</el-button>
            </div>
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
        xm: ''
      },
      tableData: [],
      page: 5,
      total: 0,
      UserId: '',
      thatUserTel: '',
      UserForce: ''
    }
  },
  methods: {
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
      const id = e.id
      this.$confirm('此操作将永久删除该角色, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        const usersName = localStorage.getItem('ms_username')
        if (usersName === null) {
          this.$router.push('/Login')
          return false
        }
        const url = localStorage.getItem('url')
        const that = this
        that.axios.get(url + 'api/api_get_role_del.php', { username: usersName, id: id }, function (res) {
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
      this.$router.push({path: '/managerrole', query: { id: e.id }})
    },
    onAdd () {
      this.$router.push({path: '/managerrole'})
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
  const url = localStorage.getItem('url')
  const xm = that.formInline.xm
  that.axios.get(url + 'api/api_get_role.php', { username: usersName, page: page, name: xm }, function (res) {
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
</style>
