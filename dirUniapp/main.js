import Vue from 'vue'
import store from '@/common/store'
Vue.prototype.$store = store
import App from './App'

import Tool from '@/common/tool';
Vue.prototype.$tool = Tool;
import Conf from '@/common/conf';
Vue.prototype.$conf = Conf;

import pageHead from '@/pages/layouts/page-head.vue'

Vue.config.productionTip = Conf.productionTip

Vue.component('page-head', pageHead)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
