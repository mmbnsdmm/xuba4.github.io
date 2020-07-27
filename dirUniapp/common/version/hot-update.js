// #ifdef APP-PLUS
import Conf from '../conf.js'
plus.runtime.getProperty(plus.runtime.appid, function() {  
    uni.request({  
        url: Conf.apiUrl + "/public/get-last-vue-app",
        success: (result) => {
            alert(result)
            // let data = result.data
            // if (data.update && data.wgtUrl) {  
            //     uni.downloadFile({  
            //         url: data.wgtUrl,  
            //         success: (downloadResult) => {  
            //             if (downloadResult.statusCode === 200) {  
            //                 plus.runtime.install(downloadResult.tempFilePath, {  
            //                     force: false  
            //                 }, function() {  
            //                     console.log('install success...');  
            //                     plus.runtime.restart();  
            //                 }, function(e) {  
            //                     console.error('install fail...');  
            //                 });  
            //             }  
            //         }  
            //     })
            // }  
        }  
    });  
});  
// #endif