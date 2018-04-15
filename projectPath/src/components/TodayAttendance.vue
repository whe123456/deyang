<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <group class="td_group">
      <cell-box class="cell_box_kq">
        <div class="date_div">
          <div class="time_div">{{time}}</div>
          <div>考勤日：{{date+week}}</div>
        </div>
      </cell-box>
      <!--<cell title="考勤制度" value="详情" is-link></cell>-->
    </group>
    <group class="qd_group">
      <cell title="当前位置" :value="now_wz"></cell>
      <cell :class="qd_cell" title="签到" inline-desc='09:00'>
        <div @click="jr_qd">
          {{qd_wb}}
        </div>
      </cell>
      <!--<cell class="qt_cell" title="签退" inline-desc='17:00' value="签退"></cell>-->
      <cell :class="zm_cell" title="周末签到">
        <div @click="zm_qd">
          {{zm_wb}}
        </div>
      </cell>
    </group>

    <toast v-model="show" :text="toasttext"></toast>
  </div>
</template>
<script>
  /* eslint-disable no-cond-assign,no-constant-condition */

  import {Toast, Group, Cell, CellBox} from 'vux'

  export default {
    components: {
      Toast,
      Group,
      Cell,
      CellBox
    },
    data () {
      return {
        time: '',
        date: '',
        week: '',
        now_wz: '11',
        now_gps: '11',
        qd_cell: 'qd_cell',
        zm_cell: 'qd_cell',
        qd_wb: '签到',
        zm_wb: '签到',
        show: false,
        toasttext: '111'
      }
    },
    mounted () {
      this.NowDate()
      const that = this
      setInterval(function () {
        that.NowDate()
      }, 1000)
      const wxid = localStorage.getItem('wxid')
      const url = localStorage.getItem('url')
      that.axios.get(url + 'api/wap_use_stu_today_qd.php', { wxid: wxid }, function (res) {
        if (res.state === 'true') {
          if (res.jrkq > 0) {
            that.qd_cell = 'qt_cell'
            that.qd_wb = '已签到'
          }
          if (res.zmkq > 0) {
            that.zm_cell = 'qt_cell'
            that.zm_wb = '已签到'
          }
        } else {
          that.$vux.alert.show({
            title: '提示',
            content: res.msg
          })
        }
      })
    },
    methods: {
      jr_qd () {
        if (this.qd_wb === '已签到') {
          return false
        }
        const wxid = localStorage.getItem('wxid')
        const url = localStorage.getItem('url')
        const nowwz = this.now_wz
        const nowgps = this.now_gps
        const that = this
        this.axios.get(url + 'api/wap_use_stu_qd.php', { wxid: wxid, type: 0, address: nowwz, gps: nowgps }, function (res) {
          if (res.state === 'true') {
            that.qd_cell = 'qt_cell'
            that.qd_wb = '已签到'
            that.toasttext = '签到成功'
            that.show = true
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      zm_qd () {
        if (this.zm_wb === '已签到') {
          return false
        }
        const wxid = localStorage.getItem('wxid')
        const url = localStorage.getItem('url')
        const nowwz = this.now_wz
        const nowgps = this.now_gps
        const that = this
        this.axios.get(url + 'api/wap_use_stu_qd.php', { wxid: wxid, type: 1, address: nowwz, gps: nowgps }, function (res) {
          if (res.state === 'true') {
            that.toasttext = '签到成功'
            that.zm_cell = 'qt_cell'
            that.zm_wb = '已签到'
            that.show = true
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      NowDate () {
        let now = new Date()
        let year = now.getFullYear()      // 年
        let month = now.getMonth() + 1     // 月
        let day = now.getDate()            // 日

        let hh = now.getHours()            // 时
        let mm = now.getMinutes()          // 分
        let ss = now.getSeconds()           // 秒
        const myddy = now.getDay()          // 获取存储当前日期
        const weekday = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六']
        this.week = weekday[myddy]
        let clock = year + '-'

        if (month < 10) clock += '0'

        clock += month + '-'

        if (day < 10) clock += '0'
        clock += day
        let time = ''
        if (hh < 10) time += '0'
        time += hh + ':'
        if (mm < 10) time += '0'
        time += mm + ':'
        if (ss < 10) time += '0'
        time += ss
        this.date = clock
        this.time = time
      }
    }
  }
</script>

<style>
  .date_div{    width: 100%;
    color: #666;
    font-size: 14px;
    text-align: center;}
  .time_div{    font-size: 35px;line-height: 60px;}
  .cell_box_kq{    padding-top: 30px!important;padding-bottom: 30px!important;}
  .qd_group{margin-top: 15px;}
  .qd_group label{color: #000;
    font-size: 18px;}
  .qd_group span{    font-size: 14px;
    color: #666;}
  .qd_cell .weui-cell__ft{width: 70px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    background: #307dcd;
    color: #fff;
    border-radius: 5px;
    border: 1px solid #2c66a1;}
  .qt_cell .weui-cell__ft{width: 70px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    background: #f8f8f8;
    color: #666;
    border-radius: 5px;
    border: 1px solid #cecece;}
</style>
