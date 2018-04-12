<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <group class="td_group">
      <cell title="类型" :value="info"></cell>
      <cell title="请假标题" :value="title"></cell>
      <cell title="审批人" :value="teacher"></cell>
      <cell title="审批意见" :value="spyj"></cell>
      <cell title="二维码图片" align-items="flex-start" primary="content">
        <div>
          <img class="previewer-demo-imgs" v-for="(item, index) in ewm_list" :src="item.src" width="100" @click="show1(index)">
          <div v-transfer-dom>
            <previewer :list="ewm_list" ref="previewer_ewm" :options="options_ewm"></previewer>
          </div>
        </div>
      </cell>
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
    <x-button style="margin-top: 15px;" type="primary" link="BACK">Back</x-button>
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
        info: '病假',
        title: '爱是一道过',
        content: '爱是一道过爱是一道过爱是一道过爱是一道过',
        list: [{
          msrc: 'http://ww1.sinaimg.cn/thumbnail/663d3650gy1fplwwcynw2j20p00b4js9.jpg',
          src: 'http://ww1.sinaimg.cn/large/663d3650gy1fplwwcynw2j20p00b4js9.jpg'
        }, {
          msrc: 'http://ww1.sinaimg.cn/thumbnail/663d3650gy1fplwwcynw2j20p00b4js9.jpg',
          src: 'http://ww1.sinaimg.cn/large/663d3650gy1fplwwcynw2j20p00b4js9.jpg'
        }, {
          msrc: 'http://ww1.sinaimg.cn/thumbnail/663d3650gy1fplwwcynw2j20p00b4js9.jpg',
          src: 'http://ww1.sinaimg.cn/large/663d3650gy1fplwwcynw2j20p00b4js9.jpg'
        }],
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
        start_ts: '2018-04-18 16:16:16',
        end_ts: '2018-04-20 16:16:16',
        teacher: '老王',
        spyj: '已同意',
        ewm_list: [{
          msrc: 'http://ww1.sinaimg.cn/thumbnail/663d3650gy1fplwu9ze86j20m80b40t2.jpg',
          src: 'http://ww1.sinaimg.cn/large/663d3650gy1fplwu9ze86j20m80b40t2.jpg'
        }],
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
    mounted () {
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
