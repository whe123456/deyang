<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <div ref="elememt">
      <tab :line-width=2 active-color='#fc378c' v-model="index">
        <tab-item class="vux-center" v-for="(item, index) in list2" :key="index" @on-item-click="handler">{{item}}</tab-item>
      </tab>
    </div>
    <swiper v-model="index" :height="sheight" :show-dots="false">
      <swiper-item :key="0">
        <scroller v-show="isShow" lock-x scrollbar-y height="-54" v-model="scrollerStatus" ref="scroller" :use-pullup="pullup" :use-pulldown="pulldown" @on-pullup-loading="onScrollBottom" @on-pulldown-loading="load_down" :pulldown-config="{downContent: '下拉刷新', upContent: '释放后更新'}" :pullup-config="upobj">
          <div class="box1">
            <form-preview class="top_div" v-for="item in list" :key="item.id" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="buttons" :name="item.id"></form-preview>
          </div>
        </scroller>
      </swiper-item>
      <swiper-item :key="1">
        <scroller v-show="isShow1" lock-x scrollbar-y height="-54" v-model="scrollerStatus1" ref="scroller1" :use-pullup="pullup1" :use-pulldown="pulldown1" @on-pullup-loading="onScrollBottom1" @on-pulldown-loading="load_down1" :pulldown-config="{downContent: '下拉刷新', upContent: '释放后更新'}" :pullup-config="upobj">
          <div class="box1">
            <form-preview class="top_div" v-for="item in list1" :key="item.id" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="buttons1" :name="item.id"></form-preview>
          </div>
        </scroller>
      </swiper-item>
    </swiper>
    <loading v-model="showloading" :text="textloading"></loading>
  </div>
</template>
<script>
  import { Tab, TabItem, FormPreview, Swiper, SwiperItem, Scroller, Loading } from 'vux'
  export default {
    components: {
      FormPreview,
      Tab,
      TabItem,
      Swiper,
      SwiperItem,
      Scroller,
      Loading
    },
    data () {
      return {
        showloading: false,
        textloading: '加载中',
        isShow: false,
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
        },
        isShow1: false,
        minid1: '',
        more1: '',
        scrollerStatus1: {
          pullupStatus: 'default',
          pulldownStatus: 'default'
        },
        pullup1: true,
        pulldown1: true,
        index: 0,
        list2: ['待审批', '已审批'],
        sheight: '100px',
        buttons: [{
          style: 'primary',
          text: '查看详情',
          onButtonClick: (name) => {
            this.$router.push({path: '/leavesp', query: { id: name }})
          }
        }],
        buttons1: [{
          style: 'primary',
          text: '查看详情',
          onButtonClick: (name) => {
            this.$router.push({path: '/leaveteachinfo', query: { id: name, type: 1 }})
          }
        }],
        list: [],
        list1: []
      }
    },
    created () {
      const url = this.$route.query.url
      if (url !== undefined) {
        localStorage.setItem('url', url)
      } else {
        localStorage.setItem('url', 'http://xs.17189.net/')
      }
      const wxid = this.$route.query.wxid
      if (wxid !== undefined) {
        localStorage.setItem('wxid', wxid)
      }
      // localStorage.setItem('wxid', '222')
      if (this.$route.query.index !== undefined) {
        this.index = Number(this.$route.query.index)
      }
      this.get_list()
      this.get_list1()
    },
    mounted () {
      this.sheight = document.documentElement.clientHeight - this.$refs.elememt.offsetHeight + 'px'
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
      handler (e) {
        this.$router.push({path: '/teachlist', query: { index: e }})
      },
      get_list () {
        const url = localStorage.getItem('url')
        const that = this
        const wxid = localStorage.getItem('wxid')
        const minid = this.minid
        this.showloading = true
        that.axios.get(url + 'api/wap_teacher_qj_list.php', { wxid: wxid, minid: minid, type: 0 }, function (res) {
          that.showloading = false
          if (res.state === 'true') {
            if (res.list && res.list.length === 0) {
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
      },
      load_down1 () {
        this.minid1 = ''
        this.get_list1()
        this.$nextTick(() => {
          this.$refs.scroller1.reset({top: 0}, '500')
        })
      },
      onScrollBottom1 () {
        if (this.more1 === false) {
          return false
        }
        this.get_list1()
        this.$refs.scroller1.reset()
      },
      get_list1 () {
        const url = localStorage.getItem('url')
        const that = this
        const wxid = localStorage.getItem('wxid')
        const minid1 = this.minid1
        this.showloading = true
        that.axios.get(url + 'api/wap_teacher_qj_list.php', { wxid: wxid, minid: minid1, type: 1 }, function (res) {
          that.showloading = false
          if (res.state === 'true') {
            if (res.list && res.list.length === 0) {
              that.scrollerStatus1.pullupStatus = 'disabled'
              that.scrollerStatus1.pulldownStatus = 'disabled'
            } else {
              that.isShow1 = true
              if (that.minid1 === '') {
                that.list1 = res.list
              } else {
                that.list1 = that.list1.concat(res.list)
              }
              that.more1 = res.more
              if (that.more1 === false) {
                that.scrollerStatus1.pullupStatus = 'disabled'
                that.pullup1 = false
              } else {
                that.scrollerStatus1.pullupStatus = 'default'
                that.pullup1 = true
              }
              that.$nextTick(() => {
                that.$refs.scroller1.reset()
              })
            }
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
          that.minid1 = res.minid
        })
      }
    }
  }
</script>

<style>
  .top_div{margin-bottom: 15px;}
</style>
