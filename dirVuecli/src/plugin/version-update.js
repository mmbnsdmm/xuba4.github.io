import Config from './config'
import {Dialog} from 'vant'
let lastVersion = Config.data.lastVueApp.lastVersion;
let forceUpdate = Config.data.lastVueApp.forceUpdate;
let appDownloadUrl = Config.data.lastVueApp.androidAppDownloadUrl;
if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
    appDownloadUrl = Config.data.lastVueApp.iosAppDownloadUrl;
}
let updateLog = Config.data.lastVueApp.updateLog;
document.addEventListener('plusready', function () {
    let plus = plus || window.plus;
    let lastVueApp = {
        download: function(url) {
            if (plus) {
                plus.nativeUI.showWaiting("下载中...");
                //创建下载管理对象
                var SX_down = plus.downloader.createDownload(url, {}, function (d, status) {
                    // 下载完成
                    if (status === 200) {
                        plus.nativeUI.closeWaiting();
                        //下载成功后的回调函数
                        plus.nativeUI.toast("下载成功，准备安装" + d.filename);
                        plus.runtime.install(
                            d.filename,
                            {},
                            function () {
                                plus.nativeUI.toast('安装成功');
                                plus.runtime.restart();
                            },
                            function (e) {
                                plus.nativeUI.toast(d.filename + '安装失败:'.JSON.stringify(e));
                                plus.runtime.quit();
                            }
                        );
                    } else {
                        alert("下载失败 " + status);
                    }
                });
                //开始下载任务
                SX_down.start();
            } else {
                window.location.href = appDownloadUrl;
            }
        }
    };
    if (!lastVersion){
        Dialog.confirm({
            title: "网络异常",
            message: "未连接网络，请检查网络"
        }).then(() => {
            plus.runtime.quit();
        })
    }
    if (lastVersion !== process.env.VUE_APP_VERSION) {
        Config.init();
        if (forceUpdate){
            Dialog.confirm({
                title: "版本强制更新",
                message: updateLog
            }).then(() => {
                lastVueApp.download(appDownloadUrl);
            }).catch(() => {
                plus.runtime.quit();
            });
        }else{
            Dialog.confirm({
                title: "版本更新",
                message: updateLog
            }).then(() => {
                lastVueApp.download(appDownloadUrl);
            }).catch(() => {});
        }
    }
});