import Vue from 'vue'
import store from '@/common/store'
Vue.prototype.$store = store
import App from './App'

import Tool from '@/common/tool';
Vue.prototype.$tool = Tool;
import Conf from '@/common/conf';
Vue.prototype.$conf = Conf;

Vue.config.productionTip = Conf.productionTip

uni.request({
    url:Conf.apiUrl + "/public/get-last-vue-app"
})

import pageHead from '@/pages/layouts/page-head.vue'

Vue.component('page-head', pageHead)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
