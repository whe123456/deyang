<template>
  <div>
    <div class="crumbs">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/UserInfo' }"><i class="el-icon-tickets"></i> 用户信息查询</el-breadcrumb-item>
        <el-breadcrumb-item>交易记录 <span class="size20">用户id-{{UserId}} 手机号-{{UserTel}} 余额-{{UserGold}}</span></el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item>
          <span class="demonstration">起始日期</span>
          <el-date-picker
            v-model="formInline.value7"
            type="daterange"
            align="right"
            unlink-panels
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd"
            :picker-options="pickerOptions2">
          </el-date-picker>
          <el-select v-model="formInline.value4" clearable placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">查询</el-button>
          <el-button type="primary" @click="oncancle">取消查询</el-button>
        </el-form-item>
        <el-form-item class="right">
          <el-button type="primary" @click="back" class="right">返回</el-button>
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
          prop="create_ts"
          label="交易时间">
        </el-table-column>
        <el-table-column
          prop="j_type.msg"
          label="交易操作">
        </el-table-column>
        <el-table-column
          prop="jb"
          label="交易数量">
          <template slot-scope="scope">
            <div :class="scope.row.j_type.color">{{scope.row.jb}}</div>
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
      formInline: {
        value7: [],
        value4: ''
      },
      tableData: [],
      page: 5,
      total: 0,
      UserId: '',
      UserTel: '',
      UserGold: '',
      options: [{
        value: '0',
        label: '转入'
      }, {
        value: '1',
        label: '转出'
      }, {
        value: '2',
        label: '日常产出'
      }]
    }
  },
  methods: {
    onSubmit () {
      getList(1, this)
    },
    oncancle () {
      this.formInline.value7 = []
      this.formInline.value4 = ''
      getList(1, this)
    },
    handleCurrentChange (e) {
      getList(e, this)
    },
    back () {
      history.back()
    }
  },
  mounted () {
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
  that.UserId = that.$route.query.userid
  that.UserTel = that.$route.query.sjhm
  that.UserGold = that.$route.query.jb
  const url = localStorage.getItem('url')
  const value7 = that.formInline.value7
  const value4 = that.formInline.value4
  that.axios.get(url + 'api/api_get_user_transation.php', { username: that.UserId, page: page, time: value7, type: value4 }, function (res) {
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
  .green{color: #67C23A;}
  .red{color: #F56C6C;}
</style>
