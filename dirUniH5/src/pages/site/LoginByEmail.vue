<template>
    <div class="login-by-email">
        <div class="container">
            <div class="col-row">
                <div class="col-xs-12">
                    <h4>邮箱登录</h4>
                    <div class="form-group">
                        <label>邮箱</label>
                        <input type="email" class="form-control" v-model="email" placeholder="请输入邮箱" required>
                    </div>
                    <div class="help-block">
                        <navigator url="/pages/site/Login" class="float-left">
                            <text class="text-blue">用户名登陆</text>
                        </navigator>
                        <text :decode="false" class="float-left">&nbsp; | &nbsp;</text>
                        <navigator url="/pages/site/About" class="float-left">
                            <text class="text-blue">注册</text>
                        </navigator>
                        <navigator url="/pages/site/About" class="float-right">
                            <text class="text-blue">忘记密码</text>
                        </navigator>
                        <div class="clearfix"></div>
                    </div>
                    <button class="btn btn-primary btn-block" @click="toLogin" v-preventReClick>登录</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Toast} from 'vant';

    export default {
        name: "LoginByEmail",
        data(){
            return {
                countDownSendLogin: "",
                isBtnSendLoginEmaildisabled: false,
                email : "",
                emailErrMsg : "",
                code : "",
                codeErrMsg : "",
                isLoginBtnDisabled: false
            }
        },
        verify: {
            email: ["required", "email"],
            code: ["required"]
        },
        methods: {
            sendLoginCode: function(){
                let _this = this;
                _this.emailErrMsg = "";
                if (!_this.$verify.check('sendLoginEmailCode')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    return ;
                }
                _this.$http.post('/site/send-email-code', {
                    email: this.email,
                    typeKey: 2
                }).then(resp => {
                    let msg = resp.data;
                    if (msg.code === 200){
                        if (msg.data.status === 200){
                            Toast("发送成功");
                            _this.countDownSendLogin = 120;
                            _this.isBtnSendLoginEmaildisabled = true;
                            let timerSendCode = setInterval(() => {
                                _this.countDownSendLogin--;
                                if (_this.countDownSendLogin <= 0) {
                                    _this.countDownSendLogin = '';
                                    _this.isBtnSendLoginEmaildisabled = false;
                                    clearInterval(timerSendCode);
                                }
                            }, 1000);
                        }else{
                            Toast(msg.data.msg);
                        }
                    }else{
                        Toast(msg.message);
                    }
                });
            },
            loginByEmail: function () {
                let _this = this;
                _this.emailErrMsg = "";
                _this.codeErrMsg = "";
                if (!_this.$verify.check('login')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    if (_this.$verify.$errors.code) {
                        _this.codeErrMsg = _this.$verify.$errors.code[0];
                    }
                    return ;
                }
                let email = _this.email,
                    code = _this.code;
                let data = {email, code};
                _this.isLoginBtnDisabled = true;
                _this.$store.dispatch('loginByEmail', data).then(resp => {
                    if (resp.data.code !== 200){
                        Toast(resp.data.message);
                        _this.isLoginBtnDisabled = false;
                    }else{
                        if (resp.data.data.status !== 200){
                            Toast(resp.data.data.msg);
                            _this.isLoginBtnDisabled = false;
                        }else{
                            Toast("登录成功");
                            this.$router.push('/');
                        }
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>