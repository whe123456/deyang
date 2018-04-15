<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <Calendar
      v-on:choseDay="clickDay"
      v-on:changeMonth="changeDate"
      :markArray=qd_date
      isHideOtherday=true
    ></Calendar>

    <group class="top_15">
      <cell :title="dateweek"></cell>
      <cell class="weui_cell_dz" v-if="rckq" title="日常考勤" value="已考勤"></cell>
      <cell class="weui_cell_dz" v-else title="日常考勤" value="暂无考勤记录"></cell>
      <cell class="weui_cell_dz" v-if="zmkq" title="周末考勤" value="已考勤"></cell>
      <cell class="weui_cell_dz" v-else title="周末考勤" value="暂无考勤记录"></cell>
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
        dateweek: '',
        qd_date: '',
        rckq: false,
        zmkq: false
      }
    },
    mounted () {
      let now = new Date()
      let year = now.getFullYear()      // 年
      let month = now.getMonth() + 1     // 月
      let day = now.getDate()            // 日
      let clock = year + '/'

      if (month < 10) clock += '0'

      clock += month + '/'

      if (day < 10) clock += '0'
      clock += day
      this.get_month_qd(clock)
      this.clickDay(clock)
    },
    methods: {
      clickDay (data) {
        const that = this
        const wxid = localStorage.getItem('wxid')
        const url = localStorage.getItem('url')
        that.axios.get(url + 'api/wap_use_stu_qd_xz_day.php', { wxid: wxid, ts: data }, function (res) {
          if (res.state === 'true') {
            that.dateweek = res.ts_info
            that.rckq = res.rckq
            that.zmkq = res.zmkq
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      changeDate (data) {
        this.get_month_qd(data)
      },
      get_month_qd (data) {
        const that = this
        const wxid = localStorage.getItem('wxid')
        const url = localStorage.getItem('url')
        that.axios.get(url + 'api/wap_use_stu_qd_list.php', { wxid: wxid, ts: data }, function (res) {
          if (res.state === 'true') {
            that.qd_date = res.list
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
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
  .top_15{margin-top: 15px;}
  .weui_cell_dz{    padding-top: 15px;
    padding-bottom: 15px;
    color: #666;
    font-size: 16px;}
</style>
