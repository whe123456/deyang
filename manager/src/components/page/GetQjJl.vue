<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 请假记录查询</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <label>班级查询</label>
          <el-input v-model="formInline.bj_mc" placeholder="请输入班级查询"></el-input>
          <label>姓名查询</label>
          <el-input v-model="formInline.xm" placeholder="请输入姓名查询"></el-input>
          <label>审核状态</label>
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
          <label>申请时间</label>
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
          fixed
          prop="id"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="bj_mc"
          fixed
          label="班级">
        </el-table-column>
        <el-table-column
          prop="xm"
          fixed
          label="姓名">
        </el-table-column>
        <el-table-column
          prop="js_xm"
          label="教师姓名">
        </el-table-column>
        <el-table-column
          prop="qj_yy"
          label="请假原因">
        </el-table-column>
        <el-table-column
          prop="sf_ty"
          label="审核状态">
          <template slot-scope="scope">
            <label v-if="scope.row.sf_ty === 0">等待审核</label>
            <label v-else-if="scope.row.sf_ty === 1">同意</label>
            <label v-else>不同意</label>
          </template>
        </el-table-column>
        <el-table-column
          prop="ewm_url"
          label="二维码图片"
        width="100">
          <template slot-scope="scope">
            <img :src="scope.row.ewm_url" class="img_90"/>
          </template>
        </el-table-column>
        <el-table-column
          prop="sh_yj"
          label="审核意见">
        </el-table-column>
        <el-table-column
          prop="qj_sj"
          label="请假时间"
          width="350">
        </el-table-column>
        <el-table-column
          prop="create_ts"
          label="申请时间"
          width="200">
        </el-table-column>
        <el-table-column
          prop="sh_sj"
          label="审核时间"
          width="200">
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
        label: '等待审核'
      }, {
        value: '1',
        label: '审核同意'
      }, {
        value: '-1',
        label: '审核未通过'
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
      window.open(url + 'api/export/export_qj_jl.php?username=' + usersName + '&bj_mc=' + Bjmc + '&sf_ty=' + KqLx + '&kq_sj=' + Kqsj + '&xm=' + xm)
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
  const url = localStorage.getItem('url')
  const Bjmc = that.formInline.bj_mc
  const KqLx = that.formInline.kq_lx
  const Kqsj = that.formInline.kq_sj
  const xm = that.formInline.xm
  that.axios.get(url + 'api/api_get_qj_list.php', {username: usersName, page: page, bj_mc: Bjmc, sf_ty: KqLx, kq_sj: Kqsj, xm: xm}, function (res) {
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
  .img_90{width: 90px;}
</style>
