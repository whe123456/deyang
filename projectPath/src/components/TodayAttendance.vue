<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <group>
      <cell-box class="cell_box_kq">
        <div class="date_div">
          <div class="time_div">{{time}}</div>
          <div>考勤日：{{date+week}}</div>
        </div>
      </cell-box>
      <cell title="考勤制度" value="详情" is-link></cell>
    </group>
    <group class="qd_group">
      <cell class="qd_cell" title="签到" inline-desc='09:00' value="签到"></cell>
      <cell class="qt_cell" title="签退" inline-desc='17:00' value="签退"></cell>
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
        time: '',
        date: '',
        week: ''
      }
    },
    mounted () {
      this.NowDate()
      const that = this
      setInterval(function () {
        that.NowDate()
      }, 1000)
    },
    methods: {
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
  .weui-cells{margin-top: 0!important;}
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
