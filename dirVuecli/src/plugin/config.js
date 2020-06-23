import $ from 'jquery'
import Tool from './tool'
import {Toast} from "vant";
// 基准大小
const baseSize = 32;
// 设置 rem 函数
function setRem () {
    // 当前页面宽度相对于 750 宽的缩放比例，可根据自己需要修改。
    const scale = document.documentElement.clientWidth / 750;
    // 设置页面根节点字体大小
    document.documentElement.style.fontSize = (baseSize * Math.min(scale, 2)) + 'px';
}
// 初始化
setRem();
// 改变窗口大小时重新设置 rem
window.onresize = function () {
    setRem()
};
let config = {
    data: {
        apiUrl: "",
        lastVueApp: {
            noticeMsg: "",
            lastVersion: "",
            forceUpdate: false,
            updateLog: "",
            androidAppDownloadUrl: "",
            iosAppDownloadUrl: "",
            webAppUrl: ""
        },
        datas: {
            adminEmail: "",
            sysInfo: [],
            howToUse: [],
            warnings: [],
            qqqs: []
        },
        emailSendCodeTypes: {}
    },
    syncPost: function(uri, formParams = {}) {
        let data = null;
        $.ajax({
            type: "post",
            url: this.data.apiUrl + uri,
            data: formParams,
            async: false,
            dataType: "json",
            success: function (resp) {
                if (resp.code !== 200){
                    Toast(resp.message);
                }
                data = resp.data;
            }
        });
        return data;
    },
    checkApiUrl: function () {
        $.ajax({
            url: config.data.apiUrl + "/public/get-last-vue-app",
            type: 'GET',
            async: false,
            complete: function(response) {
                if(response.status !== 200) {
                    document.addEventListener('plusready', function () {
                        let plus = plus || window.plus;
                        plus.nativeUI.toast("服务异常");
                        plus.runtime.quit();
                    });
                }
            }
        });
    },
    init: function () {
        let _this = this;
        $.ajax({
            type: "get",
            url: process.env.VUE_APP_GET_CONFIG_URL,
            async: false,
            dataType: "json",
            success: function (resp) {
                _this.data.apiUrl = resp.apiUrl || "";
            }
        });
        $.ajax({
            type: "post",
            url: _this.data.apiUrl + "/public/get-last-vue-app",
            async: false,
            dataType: "json",
            success: function (resp) {
                _this.data.lastVueApp = resp.data || {};
            }
        });
        $.ajax({
            type: "post",
            url: _this.data.apiUrl + "/public/get-datas",
            async: false,
            dataType: "json",
            success: function (resp) {
                _this.data.datas = resp.data.datas || {};
            }
        });
        $.ajax({
            type: "post",
            url: _this.data.apiUrl + "/public/get-enums",
            async: false,
            dataType: "json",
            success: function (resp) {
                _this.data.emailSendCodeTypes = resp.data.enums.logEmailSendCode.TypeDesc || {};
            }
        });
        Tool.setCache("APP_CONFIG_CACHE_KEY", _this.data, 3600);
        return _this.data;
    }
};
if (process.env.VUE_APP_ENV === "dev") Tool.delCache("APP_CONFIG_CACHE_KEY"); // 测试
let data = Tool.getCache("APP_CONFIG_CACHE_KEY");
if (!data || !data.apiUrl){
    config.data = config.init();
}else{
    config.data = data;
}
config.checkApiUrl();
setInterval(config.checkApiUrl, 300000);
export default config