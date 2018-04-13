<template>
  <div>
    <group title="学生注册">
      <x-input title='学号' :required="true" v-model="xh"></x-input>
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
    mounted () {
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
        const xh = this.xh
        const name = this.name
        let tel = this.tel
        const yzm = this.yzm
        if (xh === '' || name === '' || tel === '' || yzm === '') {
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
        this.$router.push({path: '/'})
      },
      change_text_fun () {
        this.xs_yf = true
        this.change_ms()
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
        xh: '',
        name: '',
        tel: '',
        yzm: '',
        xs_yf: false,
        change_text: '发送验证码',
        ms: 60
      }
    }
  }
</script>

<style>
</style>
