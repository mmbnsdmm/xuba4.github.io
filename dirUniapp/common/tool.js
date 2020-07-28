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
        let value = uni.getStorageSync(key)
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
    }
}
export default tool