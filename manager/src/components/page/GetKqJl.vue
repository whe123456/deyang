<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 考勤记录查询</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <label>班级查询</label>
          <el-input v-model="formInline.bj_mc" placeholder="请输入班级查询"></el-input>
          <label>姓名查询</label>
          <el-input v-model="formInline.xm" placeholder="请输入姓名查询"></el-input>
          <label>考勤类型</label>
          <el-select v-model="formInline.kq_lx" clearable placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <label>考勤时间</label>
          <el-date-picker
            v-model="formInline.kq_sj"
            type="datetimerange"
            :picker-options="pickerOptions2"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd HH:mm:ss"
            align="right">
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
          <el-button type="primary" @click="oncancle">取消查询</el-button>
        </el-form-item>
        <el-form-item class="right">
          <el-button type="primary" @click="onExcel">导出</el-button>
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
          prop="bj_mc"
          label="班级">
        </el-table-column>
        <el-table-column
          prop="xm"
          label="姓名">
        </el-table-column>
        <el-table-column
          prop="kq_lx"
          label="考勤类型">
          <template slot-scope="scope">
            <label v-if="scope.row.kq_lx === 0">日常考勤</label>
            <label v-else>周末考勤</label>
          </template>
        </el-table-column>
        <el-table-column
          prop="create_ts"
          label="考勤时间">
        </el-table-column>
        <el-table-column
          prop="kq_dz"
          label="考勤地址">
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
        bj_mc: '',
        kq_lx: '',
        kq_sj: '',
        xm: ''
      },
      options: [{
        value: '0',
        label: '日常考勤'
      }, {
        value: '1',
        label: '周末考勤'
      }],
      pickerOptions2: {
        shortcuts: [{
          text: '最近一周',
          onClick (picker) {
            const end = new Date()
            const start = new Date()
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
            picker.$emit('pick', [start, end])
          }
        }, {
          text: '最近一个月',
          onClick (picker) {
            const end = new Date()
            const start = new Date()
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
            picker.$emit('pick', [start, end])
          }
        }, {
          text: '最近三个月',
          onClick (picker) {
            const end = new Date()
            const start = new Date()
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
            picker.$emit('pick', [start, end])
          }
        }]
      },
      tableData: [],
      page: 5,
      total: 0
    }
  },
  methods: {
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.bj_mc = ''
      this.formInline.kq_lx = ''
      this.formInline.kq_sj = []
      this.formInline.xm = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    onExcel () {
      const usersName = localStorage.getItem('ms_username')
      if (usersName === null) {
        this.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      const Bjmc = this.formInline.bj_mc
      const KqLx = this.formInline.kq_lx
      const Kqsj = this.formInline.kq_sj
      const xm = this.formInline.xm
      window.open(url + 'api/export/export_kq_jl.php?username=' + usersName + '&bj_mc=' + Bjmc + '&kq_lx=' + KqLx + '&kq_sj=' + Kqsj + '&xm=' + xm)
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
  const Bjmc = that.formInline.bj_mc
  const KqLx = that.formInline.kq_lx
  const Kqsj = that.formInline.kq_sj
  const xm = that.formInline.xm
  that.axios.get(url + 'api/api_get_kq_list.php', {username: usersName, page: page, bj_mc: Bjmc, kq_lx: KqLx, kq_sj: Kqsj, xm: xm}, function (res) {
    if (res.state === 'true') {
      that.page = res.page
      that.total = res.count
      if (res.user !== false) {
        that.tableData = res.user
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
  .el-breadcrumb {
    font-size: 30px;
    margin-bottom: 22px;
  }

  .el-pagination {
    text-align: center;
  }

  .el-input {
    width: 200px;
  }
  .right{float:right}
</style>
