<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <popup-radio :options="options" v-model="option" placeholder="请选择类型"></popup-radio>
    <div class="jd_dw_div">类型</div>
    <group class="td_group">
      <x-input placeholder="请输入请假标题"></x-input>
      <x-textarea :max="200" name="description" placeholder="请输入请假内容"></x-textarea>
      <uploader
        :max="max_number"
        :images="xz_img"
        :show-header="false"
        :upload-url="uploadUrl"
        name="img"
        size="small"
        @preview="previewMethod"
        @add-image="addImageMethod"
        @remove-image="removeImageMethod"
      ></uploader>
      <!--../assets/downing.png-->
    </group>
    <group>
      <datetime v-model="value" placeholder="请选择开始时间" @on-change="change" clear-text="today" @on-clear="setToday":start-date="start_date" :max-year=2100 format="YYYY-MM-DD HH:mm" title="开始时间" year-row="{value}年" month-row="{value}月" day-row="{value}日" hour-row="{value}点" minute-row="{value}分" confirm-text="完成" cancel-text="取消"></datetime>
      <datetime v-model="value1" placeholder="请选择截止时间" @on-change="change1" :start-date="start_date" :max-year=2100 format="YYYY-MM-DD HH:mm" title="截止时间" year-row="{value}年" month-row="{value}月" day-row="{value}日" hour-row="{value}点" minute-row="{value}分" confirm-text="完成" cancel-text="取消"></datetime>
      <cell title="申请时长" class="weui_start">
        <div>
          <div class="margin_bottom"><span>{{xc_date}}</span> 天 <span>{{xc_ts}}</span> 小时</div>
          <div class="bottom_div">本次申请{{z_sq}}天</div>
        </div>
      </cell>
    </group>
    <group>
      <popup-radio title="审批人" :options="teacher_list" v-model="teacher" placeholder="请选择老师"></popup-radio>
    </group>
    <flexbox class="margin_top">
      <flexbox-item>
        <x-button type="default">保存草稿</x-button>
      </flexbox-item>
      <flexbox-item>
        <x-button type="primary">提交申请</x-button>
      </flexbox-item>
    </flexbox>
  </div>
</template>
<script>
  import { XTextarea, Group, XInput, PopupRadio, DatetimeRange, Cell, Datetime, Flexbox, FlexboxItem, XButton } from 'vux'
  import Uploader from 'vux-uploader'
  export default {
    components: {
      XTextarea,
      Group,
      XInput,
      PopupRadio,
      DatetimeRange,
      Datetime,
      Uploader,
      Cell,
      Flexbox,
      FlexboxItem,
      XButton
    },
    data () {
      return {
        teacher_list: [{
          key: '1',
          value: '事假'
        }, {
          key: '2',
          value: '病假'
        }],
        teacher: '2',
        value: '',
        value1: '',
        option: '',
        options: [{
          key: '1',
          value: '事假'
        }, {
          key: '2',
          value: '病假'
        }],
        start_date: '',
        uploadUrl: '',
        xz_img: [],
        max_number: 3,
        val_date: '',
        val_date1: '',
        xc_date: '0',
        xc_ts: '0',
        z_sq: '0.00'
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
      previewMethod (e) {
        console.log('change', e)
      },
      addImageMethod (val) {
        console.log('change', val)
      },
      removeImageMethod (val) {

      },
      setToday () {
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
      },
      change (e) {
        this.val_date = new Date(e)
        this.datedifference()
      },
      change1 (e) {
        this.val_date1 = new Date(e)
        this.datedifference()
      },
      datedifference () {
        if (this.val_date === '' || this.val_date1 === '') {
          return false
        }
        let sDate1 = Date.parse(this.val_date)
        let sDate2 = Date.parse(this.val_date1)
        let DateSpan = sDate2 - sDate1
        DateSpan = Math.abs(DateSpan)
        let IDays = Math.floor(DateSpan / (3600 * 1000))
        this.z_sq = Math.round(parseFloat(IDays / 24) * 100) / 100
        this.xc_date = Math.round(IDays / 24)
        this.xc_ts = Math.round(IDays % 24)
      }
    }
  }
</script>

<style>
  .jd_dw_div{position: absolute;
  padding: 10px 15px;    top: 0;}
  .weui_start{    align-items: flex-start!important;}
  .margin_bottom{margin-bottom:20px;}
  .margin_bottom>span{color: #000;
    font-size: 20px;
    letter-spacing: 2px;
    margin: 0 10px;}
  .bottom_div{font-size: 15px;}
  .margin_top{margin-top: 20px;}
</style>
