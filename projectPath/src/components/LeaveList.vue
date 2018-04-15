<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <scroller v-show="isShow" lock-x scrollbar-y height="-54" v-model="scrollerStatus" ref="scroller" :use-pullup="pullup" :use-pulldown="pulldown" @on-pullup-loading="onScrollBottom" @on-pulldown-loading="load_down" :pulldown-config="{downContent: '下拉刷新', upContent: '释放后更新'}" :pullup-config="upobj">
      <div class="box1">
        <form-preview class="top_div" v-for="item in list" :key="item.id" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="buttons" :name="item.id"></form-preview>
      </div>
    </scroller>
    <loading v-model="showloading" :text="textloading"></loading>
  </div>
</template>
<script>
  /* eslint-disable no-cond-assign,no-constant-condition */

  import { FormPreview, LoadMore, Scroller, Loading } from 'vux'
  export default {
    components: {
      FormPreview,
      Scroller,
      LoadMore,
      Loading
    },
    data () {
      return {
        showloading: false,
        textloading: '加载中',
        isShow: false,
        list: [],
        buttons: [{
          style: 'primary',
          text: '查看详情',
          onButtonClick: (name) => {
            this.$router.push({path: '/leaveinfo', query: { id: name }})
          }
        }],
        minid: '',
        more: '',
        scrollerStatus: {
          pullupStatus: 'default',
          pulldownStatus: 'default'
        },
        pullup: true,
        pulldown: true,
        upobj: {
          content: '请上拉刷新数据',
          pullUpHeight: 60,
          height: 40,
          autoRefresh: false,
          downContent: '请上拉刷新数据',
          upContent: '请上拉刷新数据',
          loadingContent: '加载中...',
          clsPrefix: 'xs-plugin-pullup-'
        }
      }
    },
    created () {
      this.get_list()
    },
    methods: {
      load_down () {
        this.minid = ''
        this.get_list()
        this.$nextTick(() => {
          this.$refs.scroller.reset({top: 0}, '500')
        })
      },
      onScrollBottom () {
        if (this.more === false) {
          return false
        }
        this.get_list()
        this.$refs.scroller.reset()
      },
      get_list () {
        const url = localStorage.getItem('url')
        const that = this
        const wxid = localStorage.getItem('wxid')
        const minid = this.minid
        this.showloading = true
        that.axios.get(url + 'api/wap_stu_qj_list.php', { wxid: wxid, minid: minid }, function (res) {
          that.showloading = false
          if (res.state === 'true') {
            if (res && res.length === 0) {
              that.scrollerStatus.pullupStatus = 'disabled'
              that.scrollerStatus.pulldownStatus = 'disabled'
            } else {
              that.isShow = true
              if (that.minid === '') {
                that.list = res.list
              } else {
                that.list = that.list.concat(res.list)
              }
              that.more = res.more
              if (that.more === false) {
                that.scrollerStatus.pullupStatus = 'disabled'
                that.pullup = false
              } else {
                that.scrollerStatus.pullupStatus = 'default'
                that.pullup = true
              }
              that.$nextTick(() => {
                that.$refs.scroller.reset()
              })
            }
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
          that.minid = res.minid
        })
      }
    }
  }
</script>

<style>
  .top_div{margin-bottom: 15px;}
</style>
