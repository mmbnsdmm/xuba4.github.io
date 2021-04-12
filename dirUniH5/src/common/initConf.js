import Tool from '@/common/tool'
import { Toast } from 'vant'
import Conf from './conf'
let initConf = {
    apiUrl: null,
    mapApiUrl: Conf.mapApiUrl,
    v_id: Conf.v_id,
    version: Conf.version,
    productionTip: Conf.productionTip,
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
// initConf.unConnected()
initConf.apiUrl = Tool.getCache('API_URL');
if(!initConf.apiUrl){
    $.ajax({
        url:initConf.mapApiUrl,
        async: false,
        datatype: "json",
        success: function(msg){
            initConf.apiUrl = msg.apiUrl;
            Tool.setCache('API_URL', initConf.apiUrl, 86400 * 3)
        },
        error: function(){
            initConf.unConnected()
        }
    })
}else{
    $.ajax({
        url: initConf.apiUrl + "/public/get-api-url",
        async: false,
        datatype: "json",
        success: function(msg){
            if(initConf.apiUrl !== msg.apiUrl){
                initConf.unConnected()
            }
        },
        error: function(){
            initConf.unConnected()
        }
    })
}
$.ajax({
    url: initConf.apiUrl + "/public/get-enums",
    async: false,
    datatype: "json",
    success: function(msg){
        initConf.serverData.enums = msg.data.enums
    },
    error: function(){
        initConf.serverData = {};
        initConf.unConnected()
    }
});
$.ajax({
    url: initConf.apiUrl + "/public/get-datas",
    async: false,
    datatype: "json",
    success: function(msg){
        initConf.serverData.datas = msg.data.datas
    },
    error: function(){
        initConf.serverData.datas = {};
        initConf.unConnected()
    }
});
console.log(initConf);
export default initConf