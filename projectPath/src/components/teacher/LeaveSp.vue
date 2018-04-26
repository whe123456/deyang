<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <group class="td_group">
      <cell title="请假标题" :value="title"></cell>
      <cell title="申请人" :value="str"></cell>
      <cell title="审批人" :value="teacher"></cell>
      <cell title="请假内容" primary="content" :value="content"></cell>
      <cell title="申请图片" align-items="flex-start" primary="content">
        <div>
          <img class="previewer-demo-img" v-for="(item, index) in list" :src="item.src" width="100" @click="show(index)">
          <div v-transfer-dom>
            <previewer :list="list" ref="previewer" :options="options"></previewer>
          </div>
        </div>
      </cell>
      <cell title="开始时间" primary="content" :value="start_ts"></cell>
      <cell title="截止时间" primary="content" :value="end_ts"></cell>
    </group>
    <group v-if="sfksp === 0">
      <popup-radio class="red_radio" title="审批状态" :options="optionzt" v-model="spzt" placeholder="是否同意"></popup-radio>
      <x-textarea :max="50" v-model="spyj" placeholder="审批意见" ></x-textarea>
    </group>
    <group v-else>
      <popup-radio title="审批状态" readonly :options="optionzt" v-model="spzt"></popup-radio>
      <cell title="审批意见" :value="spyj" v-if="spyj !== ''"></cell>
      <badge></badge>
      <popup-radio class="red_radio" v-if="jdctea === ''" title="教导处审批状态" :options="optionzt" v-model="spjdc" placeholder="是否同意"></popup-radio>
      <x-textarea v-if="jdctea === ''" :max="50" v-model="jdcyj" placeholder="教导处审批意见" ></x-textarea>
    </group>
    <x-button class="sp_btn" type="primary" @click.native="getinfo">确认</x-button>
    <x-button class="sp_btn" type="default" link="BACK">返回</x-button>
    <toast v-model="show1" :text="toasttext" @on-hide="onHide"></toast>
  </div>
</template>
<script>
  import { XButton, Previewer, TransferDom, Group, Cell, PopupRadio, XTextarea, Toast, Badge } from 'vux'
  export default {
    components: {
      Previewer,
      Group,
      Cell,
      XButton,
      PopupRadio,
      XTextarea,
      Toast,
      Badge
    },
    directives: {
      TransferDom
    },
    data () {
      return {
        optionzt: [{
          key: 1,
          value: '同意'
        }, {
          key: -1,
          value: '不同意'
        }],
        title: '',
        content: '',
        list: [],
        options: {
          getThumbBoundsFn (index) {
            // find thumbnail element
            let thumbnail = document.querySelectorAll('.previewer-demo-img')[index]
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
        },
        start_ts: '',
        end_ts: '',
        str: '',
        teacher: '',
        spzt: '',
        spyj: '',
        show1: false,
        toasttext: '',
        spjdc: '',
        jdctea: '',
        jdcyj: '',
        sh_sj: '',
        sfksp: ''
      }
    },
    created () {
      const id = this.$route.query.id
      if (id === undefined) {
        history.back()
      }
      if (this.$route.query.url !== undefined) {
        localStorage.setItem('url', this.$route.query.url)
      }
      if (this.$route.query.wxid !== undefined) {
        localStorage.setItem('wxid', this.$route.query.wxid)
      }
      const that = this
      const url = localStorage.getItem('url')
      const wxid = localStorage.getItem('wxid')
      this.sheight = document.documentElement.clientHeight - 55 + 'px'
      this.$vux.loading.show({
        text: 'Loading'
      })
      that.axios.get(url + 'api/wap_stu_qj_info.php', { id: id, wxid: wxid }, function (res) {
        that.$vux.loading.hide()
        if (res.state === 'true') {
          that.title = res.info.qj_yy
          that.content = res.info.qj_nr
          if (res.info.sq_img !== '' && res.info.sq_img !== null) {
            const str = res.info.sq_img.replace(/url/g, 'src')
            that.list = JSON.parse(str)
          }
          var arr = res.info.qj_sj
          arr = arr.split('.')
          that.start_ts = arr[0]
          that.end_ts = arr[1]
          that.teacher = res.info.xm
          that.str = res.info.stu_xm
          that.spzt = res.info.sf_ty
          that.sfksp = res.info.sf_ty
          that.spyj = res.info.sh_yj
          that.spjdc = res.info.jdc_ty
          that.jdctea = res.info.jdc_teacher
          that.jdcyj = res.info.jdc_yj
          that.sh_sj = res.info.sh_sj
        } else {
          that.$vux.alert.show({
            title: '提示',
            content: res.msg
          })
        }
      })
    },
    methods: {
      show (index) {
        this.$refs.previewer.show(index)
      },
      getinfo () {
        if (this.spzt === '' && this.spjdc === '') {
          return false
        }
        const id = this.$route.query.id
        const that = this
        const url = localStorage.getItem('url')
        const wxid = localStorage.getItem('wxid')
        this.$vux.loading.show({
          text: 'Loading'
        })
        let zt = this.spzt
        if (this.spjdc !== 0) {
          zt = this.spjdc
        }
        let yj = this.spyj
        if (this.spjdc !== 0) {
          yj = this.jdcyj
        }
        that.axios.get(url + 'api/wap_teacher_check_info.php', { id: id, wxid: wxid, zt: zt, yj: yj }, function (res) {
          that.$vux.loading.hide()
          if (res.state === 'true') {
            that.toasttext = '已审批申请'
            that.show1 = true
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      onHide () {
        history.back()
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
  .red_radio>.vux-cell-primary{color: #f74c31;}
</style>
