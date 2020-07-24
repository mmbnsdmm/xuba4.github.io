let tool = {
    async uniAsyncRequest (requestData){
        await function(){
            return new Promise(function(resolve, reject){
                uni.request({
                    url: requestData.url,
                    success(msg) {
                        console.log(5)
                        console.log(msg)
                        resolve(success)
                    },
                    fail() {
                        console.log(546)
                        reject(error)
                    }
                })
            })
        }
        console.log('后打印')
    },
    log: function(value){
        switch (typeof value) {
            case "object":
                window.console.log(JSON.stringify(value));
                break;
            default:
                window.console.log(value);
                break;
        }
    },
    foreach: function(x, fn) {
        for (let k in x) {
            if (x.prototype.hasOwnProperty.call(x, k)) {
                let result = fn.call(x, k, x[k]);
                if (result === false) {
                    break;
                }
            }
        }
    },
    getTimestamp: function(isMicro = false) {
        let _timestamp = (new Date()).valueOf();
        if (isMicro)return _timestamp;
        _timestamp = _timestamp/1000;
        _timestamp = Math.floor(_timestamp);
        return _timestamp
    }
}
export default tool