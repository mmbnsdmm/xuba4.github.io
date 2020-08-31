import Store from '../store'
import Tool from '../tool'
import Http from '../http'
import CryptoJS from 'crypto-js'
import {Toast} from 'vant'

let auth = {
    initUser: function () {
        let user = Store.getters.userInfo;
        if (!user || !user.token || !user.key){
            Toast("获取用户认证数据失败，请重新登陆");
            return;
        }
        return user;
    },
    generateFormParams: function (params = {}) {
        let user = this.initUser();
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
    post: function(uri, formParams = {}, isAsync = true, successBack, errorBack) {
        formParams = this.generateFormParams(formParams);
        return Http.post(uri, formParams, isAsync, successBack, errorBack);
    },
    updateUserInfo: function () {
        this.post("/user/center/user-info", {}, true, function (res) {
            let user = res.user;
            Store.commit("login", user);
        }, function (msg) {
            Toast(msg);
        })
    },
    _getSessionKey: function (key) {
        let user = this.initUser();
        return user.token + "_" + key;
    },
    setSession: function (key, value, duration = 0) {
        let _k = this._getSessionKey(key);
        Tool.setCache(_k, value, duration);
    },
    getSession: function (key) {
        let _k = this._getSessionKey(key);
        return Tool.getCache(_k);
    },
    delSession: function (key) {
        let _k = this._getSessionKey(key);
        Tool.delCache(_k);
    }
};
export default auth