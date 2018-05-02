<template>
  <div :style="{height: sheight}">
    <msg title="申请已提交" description="申请已提交老师审核，是否前往请假列表" :buttons="buttons"></msg>
  </div>
</template>
<script>
  import { Msg, Divider, XButton } from 'vux'
  export default {
    components: {
      Msg,
      Divider,
      XButton
    },
    data () {
      return {
        sheight: '',
        buttons: [{
          type: 'primary',
          text: '前往',
          link: '/leavelist'
        }, {
          type: 'default',
          text: '返回',
          link: '/leave'
        }]
      }
    },
    created () {
      const type = this.$route.query.type
      if (type === undefined || type === undefined) {
        this.sheight = document.documentElement.clientHeight - 55 + 'px'
      }
    },
    methods: {
    }
  }
</script>

<style>
</style>
