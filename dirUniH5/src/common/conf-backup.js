import Tool from '@/common/tool'
import { Toast } from 'vant'
let conf = {
    apiUrl: null,
    mapApiUrl: "http://xuba4.tc/api/public/get-api-url",
    v_id: 1,
    version: "4.0.0",
    productionTip: false,
    ajax: {
        method: "post",
        datatype: "json",
        normalCode: 200,
        successStatus: 200
    },
    serverData: {
        enums: {},
        datas: {}
    },
    unConnected: function(){
        Tool.delCache('API_URL');
        Toast('服务器连接失败');
    }
};
// conf.unConnected()
conf.apiUrl = Tool.getCache('API_URL');
if(!conf.apiUrl){
    $.ajax({
        url:conf.mapApiUrl,
        async: false,
        datatype: "json",
        success: function(msg){
            conf.apiUrl = msg.apiUrl;
            Tool.setCache('API_URL', conf.apiUrl, 86400 * 3)
        },
        error: function(){
            conf.unConnected()
        }
    })
}else{
    $.ajax({
        url: conf.apiUrl + "/public/get-api-url",
        async: false,
        datatype: "json",
        success: function(msg){
            if(conf.apiUrl !== msg.apiUrl){
                conf.unConnected()
            }
        },
        error: function(){
            conf.unConnected()
        }
    })
}
$.ajax({
    url: conf.apiUrl + "/public/get-enums",
    async: false,
    datatype: "json",
    success: function(msg){
        conf.serverData.enums = msg.data.enums
    },
    error: function(){
        conf.serverData = {};
        conf.unConnected()
    }
});
$.ajax({
    url: conf.apiUrl + "/public/get-datas",
    async: false,
    datatype: "json",
    success: function(msg){
        conf.serverData.datas = msg.data.datas
    },
    error: function(){
        conf.serverData.datas = {};
        conf.unConnected()
    }
});
export default conf