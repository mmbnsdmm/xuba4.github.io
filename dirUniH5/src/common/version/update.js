import Tool from '../tool'
import Conf from '../conf'
let appTypeMap = {
    android: "安卓",
    ios: "苹果"
}
uni.request({  
    url: Conf.apiUrl + "/public/get-last-vue-app",
    data: {
        type: 1
    },
    success: (result) => {
        let enums = Conf.serverData.enums.appVersion.typeDesc
        console.log(enums)
        let data = result.data.data.model
        console.log(data)
        let sysInfo = uni.getSystemInfoSync()
        let appDesc = "安卓"
        switch(sysInfo.platform){
            case "android":
                appDesc = "安卓"
                break
            case "ios":
                appDesc = "苹果"
                break
            default:
                break
        }
        console.log(sysInfo.platform)
        if (data.is_force_update && data.wgt_url) {
            console.log(123456)
            /* uni.downloadFile({  
                url: data.wgt_url,  
                success: (downloadResult) => {  
                    if (downloadResult.statusCode === 200) {  
                        plus.runtime.install(downloadResult.tempFilePath, {  
                            force: false  
                        }, function() {  
                            console.log('install success...');  
                            plus.runtime.restart();  
                        }, function(e) {  
                            console.error('install fail...');  
                        });  
                    }  
                }  
            }) */
        }  
    }  
});  