import Vue from 'vue'
import App from './App'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'

import 'font-awesome/css/font-awesome.css'

import Vant from 'vant'
import 'vant/lib/index.less'
Vue.use(Vant);

import "colorui/colorui/main.css"
import "colorui/colorui/icon.css"
import "colorui/colorui/animation.css"

import uView from "uview-ui";
Vue.use(uView);

//防多次点击，重复提交
import preventReClick from '@/common/preventReClick'

import Lodash from 'lodash'
Vue.prototype.$_ = Lodash;

import Moment from 'moment'
import 'moment/locale/zh-cn'
Moment.locale('zh-cn');
Vue.prototype.$moment = Moment;

import Tool from '@/common/tool'
Vue.prototype.$tool = Tool;

import Conf from '@/common/conf'
Vue.prototype.$conf = Conf;

import store from '@/common/store'
Vue.prototype.$store = store;

import router from '@/common/router'
import { RouterMount } from 'uni-simple-router'

Vue.config.productionTip = Conf.productionTip;

import Http from '@/common/http'
Vue.prototype.$http = Http;

import Auth from '@/common/auth'
Vue.prototype.$auth = Auth;

import pageHead from '@/pages/layouts/page-head.vue'
Vue.component('page-head', pageHead);

App.mpType = 'app';

const app = new Vue({
    store,
    ...App
});

// #ifdef H5
RouterMount(app, '#app');
// #endif

// #ifndef H5
app.$mount("#app");
// #endif
