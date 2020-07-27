import Vue from 'vue'
import jquery from 'jquery'
Vue.prototype.$jquery = jquery
import store from '@/common/store'
Vue.prototype.$store = store
import App from './App'

import Tool from '@/common/tool'
Vue.prototype.$tool = Tool

import Conf from '@/common/conf'
Conf.apiUrl = Tool.getCache('API_URL')
if(!Conf.apiUrl){
    jquery.ajax({
        url:Conf.mapApiUrl,
        async: true,
        datatype: "json",
        success: function(msg){
            Conf.apiUrl = msg.apiUrl
            Tool.setCache('API_URL', Conf.apiUrl, 86400 * 3)
        }
    })
}else{
    jquery.ajax({
        url: Tool.getCache('API_URL') + "/public/get-enums",
        async: true,
        datatype: "json",
        success: function(msg){
            Conf.serverData.enums = msg.data.enums
        },
        error: function(){
            Conf.serverData.enums = {}
            Tool.delCache('API_URL')
        }
    })
}
Vue.prototype.$conf = Conf
// console.log(Conf)

Vue.config.productionTip = Conf.productionTip

// import '@/common/version/update'
// import '@/common/version/hot-update'

import pageHead from '@/pages/layouts/page-head.vue'

Vue.component('page-head', pageHead)

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
