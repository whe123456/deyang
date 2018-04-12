<template>
  <div>
    <div v-wechat-title="$route.meta.title"></div>
    <form-preview v-for="item in list" header-label="请假标题" :header-value="item.title" :body-items="item.list" :footer-buttons="item.buttons2"></form-preview>

  </div>
</template>
<script>
  import { FormPreview } from 'vux'
  export default {
    components: {
      FormPreview
    },
    data () {
      return {
        list: [{
          title: '爱是一道光',
          list: [{
            label: '审批人',
            value: '老王'
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
          buttons2: [{
            style: 'primary',
            text: '查看详情',
            onButtonClick: (name) => {
              alert(`clicking ${name}`)
            }
          }]
        }]
      }
    },
    mounted () {
    },
    methods: {
      buttons2 () {
        console.log(1)
      }
    }
  }
</script>

<style>
</style>
