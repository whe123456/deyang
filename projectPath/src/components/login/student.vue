<template>
  <div>
    <group :title="xs_info">
      <!--<x-input title='学号' :required="true" v-model="xh"></x-input>-->
      <x-input title='姓名' :required="true" v-model="name"></x-input>
      <x-input title='手机号码' :required="true" v-model="tel" mask="999 9999 9999" :max="13" is-type="china-mobile"></x-input>
      <x-input title='验证码' :required="true" v-model="yzm" :min="6" :max="6">
        <x-button slot="right" mini :text="change_text" :disabled="xs_yf" @click.native="change_text_fun" type="primary"></x-button>
      </x-input>
    </group>
    <box gap="10px 10px">
      <x-button type="primary" @click.native="zc_bmd">注册</x-button>
    </box>
  </div>
</template>

<script>
  import { XInput, Cell, Group, XButton, Box } from 'vux'

  export default {
    components: {
      XInput,
      Group,
      Cell,
      XButton,
      Box
    },
    methods: {
      zc_bmd () {
        // const xh = this.xh
        const name = this.name
        let tel = this.tel
        const yzm = this.yzm
        // xh === '' ||
        if (name === '' || tel === '' || yzm === '') {
          return false
        }
        tel = tel.replace(/\s+/g, '')
        const myreg = /^[1][3,4,5,7,8][0-9]{9}$/
        if (!myreg.test(tel)) {
          return false
        }
        if (yzm.length < 6 || yzm.length > 6) {
          return false
        }
        const that = this
        const url = localStorage.getItem('url')
        const wxid = localStorage.getItem('wxid')
        // xh: xh,
        that.axios.get(url + 'api/wap_use_stu_yzm.php', { name: name, tel: tel, yzm: yzm, wxid: wxid }, function (res) {
          if (res.state === 'true') {
            that.$router.push({path: '/'})
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      change_text_fun () {
        // const xh = this.xh
        // xh === '' ||
        const name = this.name
        let tel = this.tel
        if (name === '' || tel === '') {
          return false
        }
        tel = tel.replace(/\s+/g, '')
        const myreg = /^[1][3,4,5,7,8][0-9]{9}$/
        if (!myreg.test(tel)) {
          return false
        }
        const that = this
        const url = localStorage.getItem('url')
        // xh: xh,
        that.axios.get(url + 'api/wap_get_stu_yzm.php', { name: name, tel: tel }, function (res) {
          if (res.state === 'true') {
            that.xs_yf = true
            that.change_ms()
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      change_ms () {
        if (this.ms > '0') {
          this.change_text = this.ms + '秒'
          const that = this
          setTimeout(function () {
            that.ms = that.ms - 1
            that.change_ms()
          }, 1000)
        } else {
          this.change_text = '发送验证码'
          this.xs_yf = false
          this.ms = 60
        }
      }
    },
    data () {
      return {
        // xh: '',
        name: '',
        tel: '',
        yzm: '',
        xs_yf: false,
        change_text: '发送验证码',
        ms: 60,
        xs_info: '学生注册'
      }
    },
    mounted () {
      const url = this.$route.query.url
      if (url !== undefined) {
        localStorage.setItem('url', url)
      }
      const wxid = this.$route.query.wxid
      if (wxid !== undefined) {
        localStorage.setItem('wxid', wxid)
      }
      const that = this
      const uri = localStorage.getItem('url')
      const wid = localStorage.getItem('wxid')
      this.$vux.loading.show({
        text: 'Loading'
      })
      that.axios.get(uri + 'api/wap_get_stu_info.php', { wxid: wid }, function (res) {
        that.$vux.loading.hide()
        if (res.state === 'true') {
          // that.xh = res.data.xh
          that.name = res.data.xm
          that.tel = res.data.sjhm
          that.xs_info = '验证信息'
        }
      })
    }
  }
</script>

<style>
</style>
