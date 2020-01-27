<template>
    <div class="resetPassword">
        <van-panel title="" desc="重置密码" status="">
            <van-cell-group>
                <van-field v-model="email" v-verify.sendResetPasswordEmailCode="email" v-verify.resetPassword="email" :error-message="emailErrMsg" required type="email" placeholder="请输入邮箱">
                    <van-button slot="button" size="small" type="primary" @click="sendResetPasswordCode" :disabled="isBtnSendResetPasswordEmaildisabled">发送验证码{{countDownSendResetPassword}}</van-button>
                </van-field>
                <van-field v-model="password" v-verify.resetPassword="password" :error-message="passwordErrMsg" required type="password" placeholder="请输入密码" />
                <van-field v-model="passwordConfirmation" v-verify.resetPassword="passwordConfirmation" :error-message="passwordConfirmationErrMsg" required type="password" placeholder="请再次输入密码" />
                <van-field v-model="code" v-verify.resetPassword="code" :error-message="codeErrMsg" required placeholder="邮箱验证码" />
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="resetPassword" :disabled="isResetBtnDisabled">重置密码</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant';

    export default {
        name: "ResetPassword",
        data(){
            return {
                countDownSendResetPassword: "",
                isBtnSendResetPasswordEmaildisabled: false,
                email : "",
                emailErrMsg : "",
                password : "",
                passwordErrMsg : "",
                passwordConfirmation : "",
                passwordConfirmationErrMsg : "",
                code : "",
                codeErrMsg : "",
                isResetBtnDisabled: false
            }
        },
        verify: {
            email: ["required", "email"],
            password: ["required", {
                minLength: 6
            }],
            passwordConfirmation: ["required"],
            code: ["required"]
        },
        methods: {
            sendResetPasswordCode: function(){
                let _this = this;
                _this.emailErrMsg = "";
                if (!_this.$verify.check('sendResetPasswordEmailCode')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    return ;
                }
                _this.$http.post('/site/send-email-code', {
                    email: this.email,
                    typeKey: 3
                }).then(resp => {
                    let msg = resp.data;
                    if (msg.code == 200){
                        if (msg.data.is_ok == 1){
                            Toast("发送成功");
                            _this.countDownSendResetPassword = 120;
                            _this.isBtnSendResetPasswordEmaildisabled = true;
                            let timerSendCode = setInterval(() => {
                                _this.countDownSendResetPassword--;
                                if (_this.countDownSendResetPassword <= 0) {
                                    _this.countDownSendResetPassword = '';
                                    _this.isBtnSendResetPasswordEmaildisabled = false;
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
            resetPassword: function () {
                let _this = this;
                _this.emailErrMsg = "";
                _this.passwordConfirmationErrMsg = "";
                _this.passwordErrMsg = "";
                _this.codeErrMsg = "";
                if (!_this.$verify.check('resetPassword')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    if (_this.$verify.$errors.password) {
                        _this.passwordErrMsg = _this.$verify.$errors.password[0];
                    }
                    if (_this.$verify.$errors.passwordConfirmation) {
                        _this.passwordConfirmationErrMsg = _this.$verify.$errors.passwordConfirmation[0];
                    }
                    if (_this.$verify.$errors.code) {
                        _this.codeErrMsg = _this.$verify.$errors.code[0];
                    }
                    return ;
                }
                if (_this.password !== _this.passwordConfirmation){
                    _this.passwordConfirmationErrMsg = "确认密码和密码不一致";
                    return ;
                }
                let email = _this.email,
                    password = _this.password,
                    code = _this.code;
                let data = {email, password, code};
                _this.isResetBtnDisabled = true;
                _this.$http.post('/site/reset-password', data).then(resp => {
                    if (resp.data.code != 200){
                        Toast(resp.data.message);
                        _this.isResetBtnDisabled = false;
                    }else{
                        if (resp.data.data.is_ok != 1){
                            Toast(resp.data.data.msg);
                            _this.isResetBtnDisabled = false;
                        }else{
                            Toast("密码重置成功");
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>