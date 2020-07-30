import Conf from '../conf'
import CryptoJS from 'crypto-js'
let auth = {
    generateFormParams: function (params = {}) {
        let user = Store.getters.user;
        let _timestamp = Tool.getTimestamp();
        let _nonce = _timestamp + "_nonce_" + Math.random() + Math.random() + Math.random() + Math.random();
        params.token = user.token;
        params.key = user.key;
        params.nonce = _nonce;
        params.timestamp = _timestamp;
        delete params["sign"];
        delete params["ufile"];
        delete params["ufile[]"];
        let keys = Object.keys(params);
        keys.sort();
        let arr = [];
        for(let index in keys){
            arr[index] = keys[index] + "=" + params[keys[index]];
        }
        let _sign = arr.join("&");
        _sign = CryptoJS.MD5(_sign).toString();
        params.sign = _sign;
        delete params.key;
        return params;
    },
    authPost: function(uri, formParams = {}, isAsync = true) {
        formParams = this.generateFormParams(formParams);
        return Http.post(uri, formParams, isAsync);
    },
    navTo(url){
        if(!this.hasLogin){
            url = '/pages/public/login';
        }
        uni.navigateTo({
            url
        })
    },
    updateUser: function () {
        Store.dispatch('updateUser', this.generateFormData({}));
    }
}
export default auth