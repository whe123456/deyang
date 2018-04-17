<template>
  <div>
    <group :title="xs_info">
      <x-input title='教师编码' :required="true" v-model="js_bm"></x-input>
      <x-input title='姓名' :required="true" v-model="name"></x-input>
      <x-input title='登录密码' :required="true" v-model="dl_mm"></x-input>
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
      that.axios.get(uri + 'api/wap_get_teacher_info.php', { wxid: wid }, function (res) {
        if (res.state === 'true') {
          that.js_bm = res.data.js_bm
          that.name = res.data.name
          that.tel = res.data.tel
          that.xs_info = '验证信息'
        }
      })
    },
    components: {
      XInput,
      Group,
      Cell,
      XButton,
      Box
    },
    methods: {
      zc_bmd () {
        const jsbm = this.js_bm
        const name = this.name
        let tel = this.tel
        const dlmm = this.dl_mm
        const yzm = this.yzm
        if (jsbm === '' || name === '' || tel === '' || yzm === '' || dlmm === '') {
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
        that.axios.get(url + 'api/wap_use_teacher_yzm.php', { js_bm: jsbm, name: name, tel: tel, yzm: yzm, dlmm: dlmm }, function (res) {
          if (res.state === 'true') {
            that.$router.push({path: '/teachlist'})
          } else {
            that.$vux.alert.show({
              title: '提示',
              content: res.msg
            })
          }
        })
      },
      change_text_fun () {
        const jsbm = this.js_bm
        const name = this.name
        const dlmm = this.dl_mm
        let tel = this.tel
        if (jsbm === '' || name === '' || tel === '' || dlmm === '') {
          return false
        }
        tel = tel.replace(/\s+/g, '')
        const myreg = /^[1][3,4,5,7,8][0-9]{9}$/
        if (!myreg.test(tel)) {
          return false
        }
        const that = this
        const url = localStorage.getItem('url')
        that.axios.get(url + 'api/wap_get_teacher_yzm.php', { js_bm: jsbm, name: name, tel: tel }, function (res) {
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
        js_bm: '',
        name: '',
        tel: '',
        yzm: '',
        xs_yf: false,
        change_text: '发送验证码',
        ms: 60,
        dl_mm: '',
        xs_info: '教师注册'
      }
    }
  }
</script>

<style>
</style>
