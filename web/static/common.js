$(function () {
    // $('.body-view-div').find('img').addClass('img-responsive');
});

let e13=$.Event("keydown");
e13.keyCode=13;

let VueBody = new Vue({
    el: "#vue-body",
    data: {
        apiBaseUri: "",
        user: {
            token: "",
            key: ""
        },
        regs: {
            mobile: /^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/,
            email: /^[A-Za-zd0-9]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/
        }
    },
    methods: {
        signFormParams: function(form_params = {}){
            let _this = this;
            let _user = _this.user;
            if (_user.token){
                form_params.token = _user.token;
                form_params.key = _user.key;
                form_params.timestamp = Date.parse(new Date())/1000;
                form_params.nonce = form_params.timestamp + Math.random() + Math.random() + Math.random() + Math.random();
                let keys = Object.keys(form_params), i, len = keys.length;
                keys.sort();
                let arr = [];
                for(let index in keys){
                    arr[index] = keys[index] + "=" + form_params[keys[index]];
                }
                let _sign = arr.join("&");
                _sign = md5(_sign);
                form_params.sign = _sign;
            }
            return form_params;
        },
        setData: function (user_info) {
            let _this = this;
            _this.$set(_this.user, "token", user_info.token);
            _this.$set(_this.user, "key", user_info.key);
        },
        isNumber: function (val) {
            let regPos = /^\d+(\.\d+)?$/; //非负浮点数
            let regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
            if(regPos.test(val) || regNeg.test(val)){
                return true;
            }else{
                return false;
            }
        }
    }
});
VueBody.apiBaseUri = ApiBaseUri;
if (UserInfo.token){
    VueBody.setData(UserInfo);
}