import {Toast} from 'vant'
document.addEventListener('plusready', function () {
    let plus = plus || window.plus;
    let webview = plus.webview.currentWebview();
    plus.key.addEventListener('backbutton', function () {
        webview.canBack(function (e) {
            if (e.canBack) {
                webview.back()
            } else {
                let first = null;
                let count = 0;
                plus.key.addEventListener(
                    'backbutton', function () {
                        if (count === 0) {
                            first = new Date().getTime();
                            setTimeout(function () {
                                count = 0;
                                first = null
                            }, 1000)
                        }
                        if (count === 1) {
                            Toast('再按一次退出应用'); // 此处可以用自定义提示
                            setTimeout(function () {
                                count = 0;
                                first = null
                            }, 1000)
                        }
                        if (count > 1){
                            if (new Date().getTime() - first < 1500) {
                                plus.runtime.quit();
                            }
                        }
                        count ++;
                    },
                    false
                );
            }
        })
    })
});