<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <Calendar
      v-on:choseDay="clickDay"
      v-on:changeMonth="changeDate"
      :markArray=[qd_date]
      isHideOtherday=true
    ></Calendar>

    <group>
      <cell title="4月1日 星期日"></cell>
      <cell title="暂无考勤记录"></cell>
    </group>
  </div>
</template>
<script>
  import {Group, Cell, CellBox} from 'vux'
  export default {
    components: {
      Group,
      Cell,
      CellBox
    },
    data () {
      return {
        qd_date: ''
      }
    },
    mounted () {
    },
    methods: {
      clickDay (data) {
        console.log(data) // 选中某天
      },
      changeDate (data) {
        // const usersName = localStorage.getItem('ms_username')
        // const that = this
        // if (usersName === null) {
        //   that.$router.push('/Login')
        //   return false
        // }
        // const url = localStorage.getItem('url')
        // that.axios.get(url + 'api/api_get_qj_list.php', {username: usersName }, function (res) {
        //   if (res.state === 'true') {
        //     that.page = res.page
        //     that.total = res.count
        //     if (res.user !== false) {
        //       that.tableData = res.user
        //     } else {
        //       that.tableData = []
        //     }
        //   } else {
        //     that.$alert(res.msg, '提示', {
        //       confirmButtonText: '确定'
        //     })
        //   }
        // })
      }
    }
  }
</script>
<style>
  .wh_content_all{background: #fff;}
  .wh_top_changge li{color:#333}
  .wh_top_changge li>div{border-color:#ccc;}
  .wh_content_all>div:nth-of-type(2){background: #f1f1f1;}
  .wh_content_item{color: #858585;}
</style>
