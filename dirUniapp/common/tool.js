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
    }
}
export default tool