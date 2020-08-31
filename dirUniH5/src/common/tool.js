let tool = {
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
    },
    setStorage: function (key, value) {
        // localStorage.setItem(key, JSON.stringify(value));
        uni.setStorageSync(key, JSON.stringify(value))
    },
    getStorage: function (key) {
        // let value = localStorage.getItem(key);
        let value = uni.getStorageSync(key);
        if (value){
            value = JSON.parse(value);
        }
        return value;
    },
    deleteStorage: function (key) {
        // localStorage.setItem(key, JSON.stringify(null));
        uni.setStorageSync(key, JSON.stringify(null))
    },
    clearStorage: function() {
        // localStorage.clear();
        uni.clearStorageSync();
    },
    setCache: function (key, value, duration = 0) {
        let _timestamp = this.getTimestamp();
        let endTimestamp = Number(_timestamp) + Number(duration);
        if (duration === 0){
            endTimestamp = 0;
        }
        let data = {
            value: value,
            endTimestamp: endTimestamp
        };
        this.setStorage(key, data);
    },
    getCache: function (key) {
        let data = this.getStorage(key);
        if (!data){
            return null;
        }
        let endTimestamp = data.endTimestamp;
        if (endTimestamp !== 0){
            let _timestamp = this.getTimestamp();
            if (_timestamp > endTimestamp){
                return null;
            }
        }
        return data.value;
    },
    delCache: function (key) {
        this.deleteStorage(key);
    },
    delAllCache: function () {
        this.clearStorage();
    },
    clone: function (v) {
        this.setCache("clone", v);
        return this.getCache("clone");
    },
    vueArrayClear: function (arr) {
        let length = arr.length;
        arr.splice(0, length);
    },
    vueArrayReset: function (arr, arrVal) {
        this.vueArrayClear(arr);
        arrVal.forEach(function (v) {
            arr.push(v);
        })
    },
    blobToBase64: function (blob, callback) {
        let reader = new FileReader();
        reader.onload = function (e) {
            callback(e.target.result);
        };
        reader.readAsDataURL(blob);
    },
    base64toBlob: function (base64Data) {
        //console.log(base64Data);//data:image/png;base64,
        let byteString;
        if(base64Data.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(base64Data.split(',')[1]);//base64 解码
        else{
            byteString = unescape(base64Data.split(',')[1]);
        }
        let mimeString = base64Data.split(',')[0].split(':')[1].split(';')[0];//mime类型 -- image/png

        // var arrayBuffer = new ArrayBuffer(byteString.length); //创建缓冲数组
        // var ia = new Uint8Array(arrayBuffer);//创建视图
        let ia = new Uint8Array(byteString.length);//创建视图
        for(let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        let blob = new Blob([ia], {
            type: mimeString
        });
        return blob;
    }
};
export default tool