var yii_widgets = [];
$(function () {
    // $('.body-view-div').find('img').addClass('img-responsive');
});

var vue_body = new Vue({
    el: ".vue-body",
    data: {
        token: "",
        key: "",
        avatar: "",
        idcard: "",
        realname: "",
        is_courier: "",
        dot_belong: "",
        amount: "",
        frozen: "",
        deposit: "",
        reg_email: /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/
    },
    methods: {
        signFormParams: function(form_params = {}){
            var _this = this;
            this.token = window.localStorage.getItem('token');
            if (this.token != ""){
                form_params.token = this.token;
                form_params.key = this.key = window.localStorage.getItem('key');
                this.avatar = window.localStorage.getItem('avatar');
                this.idcard = window.localStorage.getItem('idcard');
                this.realname = window.localStorage.getItem('realname');
                this.is_courier = window.localStorage.getItem('is_courier');
                this.dot_belong = window.localStorage.getItem('dot_belong');
                this.amount = window.localStorage.getItem('amount');
                this.frozen = window.localStorage.getItem('frozen');
                this.deposit = window.localStorage.getItem('deposit');
                form_params.timestamp = Date.parse(new Date())/1000;
                form_params.nonce = form_params.timestamp + Math.random() + Math.random() + Math.random() + Math.random();
                var keys = Object.keys(form_params), i, len = keys.length;
                keys.sort();
                var _sign = "";
                var firstpass = true;
                for(var index in keys){
                    if(!firstpass){
                        _sign += "&";
                    }
                    _sign += keys[index] + "=" + form_params[keys[index]];
                    firstpass = false;
                }
                _sign = md5(_sign);
                form_params.sign = _sign;
            }
            return form_params;
        }
    }
});