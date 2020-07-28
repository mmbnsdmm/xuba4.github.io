import Vue from 'vue'
import jquery from 'jquery'
Vue.prototype.$jquery = jquery
import store from '@/common/store'
Vue.prototype.$store = store
import App from './App'

import Tool from '@/common/tool'
Vue.prototype.$tool = Tool

import Conf from '@/common/conf'
Vue.prototype.$conf = Conf

Vue.config.productionTip = Conf.productionTip

import Http from '@/common/http'
Vue.prototype.$http = Http

import Auth from '@/common/auth'
Vue.prototype.$auth = Auth

// import '@/common/version/update'
// import '@/common/version/hot-update'

import pageHead from '@/pages/layouts/page-head.vue'

Vue.component('page-head', pageHead)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
