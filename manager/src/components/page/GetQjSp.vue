<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><i class="el-icon-tickets"></i> 请假记录审批</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <label>手机号码查询</label>
          <el-input v-model="formInline.sjhm" placeholder="请输入手机号码查询"></el-input>
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
      </el-form>
    </div>
    <div v-loading="loading">
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
            <label v-else-if="scope.row.sf_ty === 1&&scope.row.jdc_ty === 0">等待学生处审核</label>
            <label v-else-if="scope.row.sf_ty === 1&&scope.row.jdc_ty === 1">学生处审核同意</label>
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
        <el-table-column
          prop="change"
          fixed
          label="操作" >
          <template slot-scope="scope">
            <el-button @click="ChangeClick(scope.row)" type="text" size="small" v-if="scope.row.sf_ty === 0">待审核</el-button>
            <el-button @click="ChangeClick(scope.row)" type="text" size="small" v-else-if="scope.row.sf_ty === 1&&scope.row.jdc_ty === 0&& js_id===4">待审核</el-button>
            <label v-else>已审核</label>
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
    <el-dialog title="审核" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item label="请假内容" :label-width="formLabelWidth">
          <el-input v-model="form.qj_yy" auto-complete="off" :disabled="true"></el-input>
        </el-form-item>
        <div v-if="sfksp===0">
          <el-form-item label="审批状态" :label-width="formLabelWidth">
            <el-select v-model="form.spzt" placeholder="请选择审批状态">
              <el-option
                v-for="item in optionzt"
                :key="item.key"
                :label="item.value"
                :value="item.key"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="审批意见" :label-width="formLabelWidth">
            <el-input v-model="form.spyj" auto-complete="off"></el-input>
          </el-form-item>
        </div>
        <div v-else>
          <el-form-item label="审批状态" :label-width="formLabelWidth">
            <el-input v-model="form.spzt" auto-complete="off" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="审批意见" :label-width="formLabelWidth">
            <el-input v-model="form.spyj" auto-complete="off" :disabled="true"></el-input>
          </el-form-item>
        </div>
        <div v-if="js_id===4">
          <el-form-item label="学生处审批状态" :label-width="formLabelWidth">
            <el-select v-model="form.spjdc" placeholder="请选择审批状态">
              <el-option
                v-for="item in optionzt"
                :key="item.key"
                :label="item.value"
                :value="item.key"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="学生处审批意见" :label-width="formLabelWidth">
            <el-input v-model="form.jdcyj" auto-complete="off"></el-input>
          </el-form-item>
        </div>
      </el-form>
      <div slot="footer" class="dialog-center">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="fh">确认</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data () {
    return {
      formInline: {
        sjhm: '',
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
      loading: true,
      tableData: [],
      page: 5,
      total: 0,
      form: {
        qj_yy: '',
        spzt: '',
        spyj: '',
        spjdc: '',
        jdcyj: '',
        id: ''
      },
      optionzt: [{
        key: 0,
        value: '未审核'
      }, {
        key: 1,
        value: '同意'
      }, {
        key: -1,
        value: '不同意'
      }],
      formLabelWidth: '120px',
      dialogFormVisible: false,
      js_id: 1,
      sfksp: 0,
      jdcsp: 0
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
      this.formInline.sjhm = ''
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
      const sjhm = this.formInline.sjhm
      window.open(url + 'api/export/export_qj_jl.php?username=' + usersName + '&bj_mc=' + Bjmc + '&sf_ty=' + KqLx + '&kq_sj=' + Kqsj + '&xm=' + xm + '&sjhm=' + sjhm)
    },
    fh () {
      if (this.form.kdbh === '') {
        return false
      }
      const that = this
      const usersName = sessionStorage.getItem('ms_username')
      if (usersName === null) {
        that.$router.push('/Login')
        return false
      }
      const url = localStorage.getItem('url')
      var zt = 0
      console.log(zt)
      var yj = ''
      if (that.sfksp === 0) {
        zt = that.form.spzt
        yj = that.form.spyj
      } else if (that.js_id === 4 && that.jdcsp === 0) {
        zt = that.form.spjdc
        yj = that.form.jdcyj
      }
      const id = that.form.id
      if (zt === 0 || id === '') {
        that.dialogFormVisible = false
        return false
      }
      that.axios.get(url + 'api/wap_teacher_check_info.php', {username: usersName, zt: zt, id: id, yj: yj, from: 'pc'}, function (res) {
        if (res.state === 'true') {
          that.dialogFormVisible = false
          getList(1, that)
        } else {
          that.$alert(res.msg, '提示', {
            confirmButtonText: '确定'
          })
        }
      })
    },
    ChangeClick (e) {
      console.log(e)
      this.sfksp = e.sf_ty
      this.jdcsp = e.jdc_ty
      this.form = {
        qj_yy: e.qj_yy,
        spzt: e.sf_ty,
        spyj: e.sh_yj,
        spjdc: e.jdc_ty,
        jdcyj: e.jdc_yj,
        id: e.id
      }
      this.dialogFormVisible = true
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
  const Bjmc = that.formInline.bj_mc
  const KqLx = that.formInline.kq_lx
  const Kqsj = that.formInline.kq_sj
  const xm = that.formInline.xm
  const sjhm = that.formInline.sjhm
  that.axios.get(url + 'api/api_get_qj_ysp.php', {username: usersName, page: page, bj_mc: Bjmc, sf_ty: KqLx, kq_sj: Kqsj, xm: xm, sjhm: sjhm}, function (res) {
    that.loading = false
    if (res.state === 'true') {
      that.page = res.page
      that.total = res.count
      if (res.user !== false) {
        that.tableData = res.user
      } else {
        that.tableData = []
      }
      that.js_id = res.js_id
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
