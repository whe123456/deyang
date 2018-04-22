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
      <!--<cell :class="zm_cell" title="周末签到">-->
        <!--<div @click="zm_qd">-->
          <!--{{zm_wb}}-->
        <!--</div>-->
      <!--</cell>-->
      <cell title="二维码图片" align-items="flex-start" primary="content" >
        <div>
          <img class="previewer-demo-imgs" v-for="(item, index) in ewm_list" :src="item.src" width="100" @click="show1(index)">
          <div>
            <previewer :list="ewm_list" ref="previewer_ewm" :options="options_ewm"></previewer>
          </div>
        </div>
      </cell>
    </group>

    <toast v-model="show" :text="toasttext"></toast>
  </div>
</template>
<script>
  /* eslint-disable no-cond-assign,no-constant-condition,standard/object-curly-even-spacing */
  import {Toast, Group, Cell, CellBox, Previewer } from 'vux'
  import wx from 'weixin-js-sdk'
  export default {
    components: {
      Toast,
      Group,
      Cell,
      CellBox,
      Previewer,
      wx
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
        toasttext: '111',
        ewm_list: [],
        options_ewm: {
          getThumbBoundsFn (index) {
            // find thumbnail element
            let thumbnail = document.querySelectorAll('.previewer-demo-imgs')[index]
            // get window scroll Y
            let pageYScroll = window.pageYOffset || document.documentElement.scrollTop
            // optionally get horizontal scroll
            // get position of element relative to viewport
            let rect = thumbnail.getBoundingClientRect()
            // w = width
            return {x: rect.left, y: rect.top + pageYScroll, w: rect.width}
            // Good guide on how to get element coordinates:
            // http://javascript.info/tutorial/coordinates
          }
        }
      }
    },
    created () {
      this.NowDate()
      const that = this
      setInterval(function () {
        that.NowDate()
      }, 1000)
      // console.log(localStorage)
      const wxid = localStorage.getItem('wxid')
      const url = localStorage.getItem('url')
      console.log()
      that.axios.get(url + 'api/wap_use_stu_today_qd.php', { wxid: wxid, url: encodeURIComponent(location.href.split('#')[0]) }, function (res) {
        if (res.state === 'true') {
          wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: res.config.wxapp, // 必填，企业号的唯一标识，此处填写企业号corpid
            timestamp: res.config.timestamp, // 必填，生成签名的时间戳
            nonceStr: res.config.Str, // 必填，生成签名的随机串
            signature: res.config.sign, // 必填，签名，见附录1
            jsApiList: ['getLocation'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
          })
          wx.ready(function () {
            wx.getLocation({
              type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
              success: function (res) {
                // console.log(res)
                // var latitude = res.latitude // 纬度，浮点数，范围为90 ~ -90
                // var longitude = res.longitude // 经度，浮点数，范围为180 ~ -180。
                // var speed = res.speed // 速度，以米/每秒计
                // var accuracy = res.accuracy // 位置精度
              }
            })
            if (res.jrkq > 0) {
              that.qd_cell = 'qt_cell'
              that.qd_wb = '已签到'
            }
            if (res.ewm_url !== '' && res.ewm_url !== null) {
              const ewmarr = [{'src': res.ewm_url}]
              that.ewm_list = ewmarr
            }
            // if (res.zmkq > 0) {
            //   that.zm_cell = 'qt_cell'
            //   that.zm_wb = '已签到'
            // }
          })
          wx.error(function (res) {
            alert(res.errMsg)
          })
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
      // zm_qd () {
      //   if (this.zm_wb === '已签到') {
      //     return false
      //   }
      //   const wxid = localStorage.getItem('wxid')
      //   const url = localStorage.getItem('url')
      //   const nowwz = this.now_wz
      //   const nowgps = this.now_gps
      //   const that = this
      //   this.axios.get(url + 'api/wap_use_stu_qd.php', { wxid: wxid, type: 1, address: nowwz, gps: nowgps }, function (res) {
      //     if (res.state === 'true') {
      //       that.toasttext = '签到成功'
      //       that.zm_cell = 'qt_cell'
      //       that.zm_wb = '已签到'
      //       that.show = true
      //     } else {
      //       that.$vux.alert.show({
      //         title: '提示',
      //         content: res.msg
      //       })
      //     }
      //   })
      // },
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
      },
      show1 (index) {
        this.$refs.previewer_ewm.show(index)
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
