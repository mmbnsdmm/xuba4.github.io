<template>
    <div class="login-by-email">
        <van-panel title="" desc="邮箱登录" status="">
            <van-cell-group>
                <van-field v-model="email" v-verify.sendLoginEmailCode="email" v-verify.login="email" :error-message="emailErrMsg" required type="email" placeholder="请输入邮箱">
                    <van-button slot="button" size="small" type="primary" @click="sendLoginCode" :disabled="isBtnSendLoginEmaildisabled">发送验证码{{countDownSendLogin}}</van-button>
                </van-field>
                <van-field v-model="code" v-verify.login="code" :error-message="codeErrMsg" required placeholder="邮箱验证码" />
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="loginByEmail" :disabled="isLoginBtnDisabled">登录</van-button>
            </div>
        </van-panel>
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
                    if (msg.code == 200){
                        if (msg.data.is_ok == 1){
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
                    if (resp.data.code != 200){
                        Toast(resp.data.message);
                        _this.isLoginBtnDisabled = false;
                    }else{
                        if (resp.data.data.is_ok != 1){
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