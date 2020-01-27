import Vue from 'vue'
import App from './App.vue'
import $ from 'jquery';
var jQuery = $;
var jquery = jQuery;
window.$ = window.jQuery = window.jquery = jquery;
import Tool from './plugin/tool'
import Conf from './plugin/config'
import Auth from './plugin/auth'
import router from './plugin/router'
import store from './plugin/store'
import Vant from 'vant'
import 'vant/lib/index.css';
import verify from './plugin/vue-verify-plugin/index'
import verifyConf from './plugin/vue-verify-plugin/config'
import Axios from "./plugin/axios";
import "lib-flexible/flexible.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/css/bootstrap-theme.min.css"
import "bootstrap"
import 'font-awesome/css/font-awesome.css'
import vmodal from 'vue-js-modal'
import uploader from 'vue-simple-uploader'
import './plugin/version-update'
import './plugin/back'
import './plugin/offline'
import  Editor from './components/vue-quill-editor/Editor'

Vue.prototype.$tool = Tool;
Vue.prototype.$conf = Conf;
Vue.prototype.$http = Axios;
Vue.prototype.$auth = Auth;
Vue.use(Vant);
Vue.use(verify, verifyConf);
Vue.use(vmodal);
Vue.use(uploader);
Vue.component("Editor", Editor);
Vue.config.productionTip = false;
Vue.config.devtools = true;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
