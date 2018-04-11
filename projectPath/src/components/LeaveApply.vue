<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <popup-radio :options="options" v-model="option" placeholder="请选择类型"></popup-radio>
    <div class="jd_dw_div">类型</div>
    <group class="td_group">
      <x-input placeholder="请输入请假标题"></x-input>
      <x-textarea :max="200" name="description" placeholder="请输入请假内容"></x-textarea>
      <uploader
        :max="varmax"
        :images="images"
        :handle-click="true"
        :show-header="false"
        :readonly="true"
        :upload-url="uploadUrl"
        name="img"
        :params="params"
        size="small"
        @preview="previewMethod"
        @add-image="addImageMethod"
        @remove-image="removeImageMethod"
      ></uploader>
    </group>
    <group>
      <datetime v-model="value" placeholder="请选择开始时间" clear-text="today" @on-clear="setToday":start-date="start_date" :max-year=2100 format="YYYY-MM-DD HH:mm" @on-change="change" title="开始时间" year-row="{value}年" month-row="{value}月" day-row="{value}日" hour-row="{value}点" minute-row="{value}分" confirm-text="完成" cancel-text="取消"></datetime>
      <datetime v-model="value1" placeholder="请选择截止时间" :start-date="start_date" :max-year=2100 format="YYYY-MM-DD HH:mm" @on-change="change" title="截止时间" year-row="{value}年" month-row="{value}月" day-row="{value}日" hour-row="{value}点" minute-row="{value}分" confirm-text="完成" cancel-text="取消"></datetime>

    </group>
  </div>
</template>
<script>
  import { XTextarea, Group, XInput, PopupRadio, DatetimeRange, Datetime } from 'vux'
  import Uploader from 'vux-uploader'

  export default {
    components: {
      XTextarea,
      Group,
      XInput,
      PopupRadio,
      DatetimeRange,
      Datetime,
      Uploader
    },
    data () {
      return {
        value: '',
        value1: '',
        option: '',
        options: [{
          key: 'A',
          value: 'label A'
        }, {
          key: 'B',
          value: 'label B'
        }],
        start_date: ''
      }
    },
    mounted () {
      let now = new Date()
      let year = now.getFullYear()      // 年
      let month = now.getMonth() + 1     // 月
      let day = now.getDate()            // 日

      let clock = year + '-'

      if (month < 10) clock += '0'

      clock += month + '-'

      if (day < 10) clock += '0'
      clock += day
      this.start_date = clock
    },
    methods: {
      change (e) {
        console.log('change', e)
      },
      onChange (val) {
        console.log('change', val)
      },
      onChange1 (val) {

      },
      setToday (value) {
        let now = new Date()
        let cmonth = now.getMonth() + 1
        let day = now.getDate()
        if (cmonth < 10) cmonth = '0' + cmonth
        if (day < 10) day = '0' + day
        let hh = now.getHours()            // 时
        let mm = now.getMinutes()          // 分
        if (hh < 10) hh = '0' + hh
        if (mm < 10) mm = '0' + mm
        this.value = now.getFullYear() + '-' + cmonth + '-' + day + ' ' + hh + ':' + mm
      }
    }
  }
</script>

<style>
  .td_group>div{margin-top: 0!important;}
  .jd_dw_div{position: absolute;
  padding: 10px 15px;    top: 0;}
</style>
