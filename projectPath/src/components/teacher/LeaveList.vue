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
        <form-preview v-for="item in list" :key="item.id" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="item.buttons" :name="item.id"></form-preview>
      </swiper-item>
      <swiper-item :key="1">
        <form-preview v-for="item in list1" :key="item.id" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="item.buttons" :name="item.id"></form-preview>
      </swiper-item>
    </swiper>
  </div>
</template>
<script>
  import { Tab, TabItem, FormPreview, Swiper, SwiperItem } from 'vux'
  export default {
    components: {
      FormPreview,
      Tab,
      TabItem,
      Swiper,
      SwiperItem
    },
    data () {
      return {
        index: 0,
        list2: ['待审批', '未审批'],
        sheight: '100px',
        list: [{
          title: '爱是一道光',
          id: '1',
          list: [{
            label: '审批人',
            value: '老王'
          }, {
            label: '审批状态',
            value: '待审核'
          }, {
            label: '请假类型',
            value: '病假'
          }, {
            label: '请假开始时间',
            value: '2018-04-08 15:00:00'
          }, {
            label: '请假结束时间',
            value: '2018-04-09 15:00:00'
          }],
          buttons: [{
            style: 'primary',
            text: '查看详情',
            onButtonClick: (name) => {
              this.$router.push({path: '/leavesp', query: { id: name }})
            }
          }]
        }],
        list1: [{
          title: '爱是一道光',
          id: '1',
          list: [{
            label: '审批人',
            value: '老王'
          }, {
            label: '审批状态',
            value: '待审核'
          }, {
            label: '请假类型',
            value: '病假'
          }, {
            label: '请假开始时间',
            value: '2018-04-08 15:00:00'
          }, {
            label: '请假结束时间',
            value: '2018-04-09 15:00:00'
          }],
          buttons: [{
            style: 'primary',
            text: '查看详情',
            onButtonClick: (name) => {
              this.$router.push({path: '/leaveteachinfo', query: { id: name }})
            }
          }]
        }]
      }
    },
    created () {
      if (this.$route.query.index !== undefined) {
        this.index = Number(this.$route.query.index)
      }
    },
    mounted () {
      this.sheight = document.documentElement.clientHeight - this.$refs.elememt.offsetHeight + 'px'
    },
    methods: {
      handler (e) {
        this.$router.push({path: '/teachlist', query: { index: e }})
      }
    }
  }
</script>

<style>
</style>
