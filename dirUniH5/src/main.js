import Vue from 'vue'
import App from './App'

//防多次点击，重复提交
import preventReClick from '@/common/preventReClick'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'

import Vant from 'vant'
import 'vant/lib/index.less'
Vue.use(Vant)

import "colorui/colorui/main.css";
import "colorui/colorui/icon.css";
import "colorui/colorui/animation.css";

import store from '@/common/store'
Vue.prototype.$store = store

import Tool from '@/common/tool'
Vue.prototype.$tool = Tool

import Conf from '@/common/conf'
Vue.prototype.$conf = Conf

Vue.config.productionTip = Conf.productionTip

import Http from '@/common/http'
Vue.prototype.$http = Http

import Auth from '@/common/auth'
Vue.prototype.$auth = Auth

import pageHead from '@/pages/layouts/page-head.vue'
Vue.component('page-head', pageHead)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
