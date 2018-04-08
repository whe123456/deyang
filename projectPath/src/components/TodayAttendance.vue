<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <group>
      <cell-box>
        <div class="date_div">
          <div class="time_div">{{time}}</div>
          <div>考勤日：{{date+week}}</div>
        </div>
      </cell-box>
      <cell title="考勤制度" value="详情" is-link></cell>
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
  .weui-cells{margin-top: 0;}
  .weui-cell{    padding-top: 30px;
    padding-bottom: 30px;}
</style>
