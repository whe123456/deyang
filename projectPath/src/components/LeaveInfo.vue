<template>
  <div :style="{height: sheight}" style="overflow: auto;">
    <div v-wechat-title="$route.meta.title"></div>
    <group class="td_group">
      <cell title="请假标题" :value="title"></cell>
      <cell title="申请人" :value="str"></cell>
      <cell title="审批人" :value="teacher"></cell>
      <cell title="二维码图片" align-items="flex-start" primary="content" v-if="ewm_list.length>0">
        <div>
          <img class="previewer-demo-imgs" v-for="(item, index) in ewm_list" :src="item.src" width="100" @click="show1(index)">
          <div v-transfer-dom>
            <previewer :list="ewm_list" ref="previewer_ewm" :options="options_ewm"></previewer>
          </div>
        </div>
      </cell>
      <cell title="请假内容" primary="content" :value="content"></cell>
      <cell title="申请图片" align-items="flex-start" primary="content" v-if="list.length>0">
        <div>
          <img class="previewer-demo-img" v-for="(item, index) in list" :src="item.src" width="100" @click="show(index)">
          <div v-transfer-dom>
            <previewer :list="list" ref="previewer" :options="options"></previewer>
          </div>
        </div>
      </cell>
      <cell title="开始时间" primary="content" :value="start_ts"></cell>
      <cell title="截止时间" primary="content" :value="end_ts"></cell>
      <cell title="审批状态" :value="spzt"></cell>
      <cell title="审批意见" :value="spyj" v-if="spyj !== ''"></cell>
      <cell v-if="sptea !== false" title="教导处审批人" :value="sptea"></cell>
      <cell v-if="sptea !== false" title="教导处审批状态" :value="spjdc"></cell>
      <cell v-if="sptea !== false && jdcyj !== ''" title="教导处审批意见" :value="jdcyj"></cell>
    </group>
    <x-button class="sp_btn" type="primary" link="BACK">确认</x-button>
  </div>
</template>
<script>
  import { XButton, Previewer, TransferDom, Group, Cell } from 'vux'
  export default {
    components: {
      Previewer,
      Group,
      Cell,
      XButton
    },
    directives: {
      TransferDom
    },
    data () {
      return {
        sheight: '',
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
        teacher: '',
        str: '',
        spzt: '',
        spyj: '',
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
        },
        spjdc: '',
        sptea: false,
        jdcyj: ''
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
      const type = this.$route.query.type
      if (type === undefined || type === undefined) {
        this.sheight = document.documentElement.clientHeight - 55 + 'px'
      }
      this.$vux.loading.show({
        text: 'Loading'
      })
      that.axios.get(url + 'api/wap_stu_qj_info.php', { id: id, wxid: wxid }, function (res) {
        that.$vux.loading.hide()
        if (res.state === 'true') {
          if (res.info.ewm_url !== '' && res.info.ewm_url !== null) {
            const ewmarr = [{'src': res.info.ewm_url}]
            that.ewm_list = ewmarr
          }
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
          if (res.info.sf_ty === 0) {
            that.spzt = '等待审核'
          } else if (res.info.sf_ty === 1) {
            that.spzt = '已同意'
          } else {
            that.spzt = '未同意'
          }
          that.spyj = res.info.sh_yj
          if (res.info.jdc_ty === 0) {
            that.spjdc = '等待审核'
          } else if (res.info.jdc_ty === 1) {
            that.spjdc = '已同意'
          } else {
            that.spjdc = '未同意'
          }
          that.sptea = res.info.js_xm
          that.jdcyj = res.info.jdc_yj
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
      show1 (index) {
        this.$refs.previewer_ewm.show(index)
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
