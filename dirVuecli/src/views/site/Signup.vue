<template>
    <div class="signup">
        <van-panel title="" desc="用户注册" status="">
            <van-cell-group>
                <van-field v-model="email" v-verify.sendSignupEmailCode="email" v-verify.signup="email" :error-message="emailErrMsg" required type="email" placeholder="请输入邮箱">
                    <van-button slot="button" size="small" type="primary" @click="sendSignupCode" :disabled="isBtnSendSignupEmaildisabled">发送验证码{{countDownSendSignup}}</van-button>
                </van-field>
                <van-field v-model="username" v-verify.signup="username" :error-message="usernameErrMsg" required placeholder="请输入用户名" />
                <van-field v-model="password" v-verify.signup="password" :error-message="passwordErrMsg" required type="password" placeholder="请输入密码" />
                <van-field v-model="passwordConfirmation" v-verify.signup="passwordConfirmation" :error-message="passwordConfirmationErrMsg" required type="password" placeholder="请再次输入密码" />
                <van-field v-model="code" v-verify.signup="code" :error-message="codeErrMsg" required placeholder="邮箱验证码" />
                <van-field v-model="joinMsg" v-verify.signup="joinMsg" :error-message="joinMsgErrMsg" required rows="2" autosize label="注册理由" type="textarea" maxlength="50" placeholder="请输入爱好或为什么注册信息" show-word-limit/>
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="signup" :disabled="isSignupBtnDisabled">注册</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant';

    export default {
        name: "Signup",
        data(){
            return {
                countDownSendSignup: "",
                isBtnSendSignupEmaildisabled: false,
                username : "",
                usernameErrMsg : "",
                email : "",
                emailErrMsg : "",
                password : "",
                passwordErrMsg : "",
                passwordConfirmation : "",
                passwordConfirmationErrMsg : "",
                joinMsg: "",
                joinMsgErrMsg: "",
                code : "",
                codeErrMsg : "",
                isSignupBtnDisabled: false
            }
        },
        verify: {
            email: ["required", "email"],
            username: ["required", {
                minLength: 6
            }],
            password: ["required", {
                minLength: 6
            }],
            passwordConfirmation: ["required"],
            code: ["required"],
            joinMsg: ["required", {
                maxLength: 50
            }]
        },
        methods: {
            sendSignupCode: function(){
                let _this = this;
                _this.emailErrMsg = "";
                if (!_this.$verify.check('sendSignupEmailCode')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    return ;
                }
                _this.$http.post('/site/send-email-code', {
                    email: this.email,
                    typeKey: 1
                }).then(resp => {
                    let msg = resp.data;
                    if (msg.code == 200){
                        if (msg.data.is_ok == 1){
                            Toast("发送成功");
                            _this.countDownSendSignup = 120;
                            _this.isBtnSendSignupEmaildisabled = true;
                            let timerSendCode = setInterval(() => {
                                _this.countDownSendSignup--;
                                if (_this.countDownSendSignup <= 0) {
                                    _this.countDownSendSignup = '';
                                    _this.isBtnSendSignupEmaildisabled = false;
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
            signup: function () {
                let _this = this;
                _this.emailErrMsg = "";
                _this.usernameErrMsg = "";
                _this.passwordConfirmationErrMsg = "";
                _this.passwordErrMsg = "";
                _this.codeErrMsg = "";
                _this.joinMsgErrMsg = "";
                if (!_this.$verify.check('signup')) {
                    if (_this.$verify.$errors.email) {
                        _this.emailErrMsg = _this.$verify.$errors.email[0];
                    }
                    if (_this.$verify.$errors.username) {
                        _this.usernameErrMsg = _this.$verify.$errors.username[0];
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
                    if (_this.$verify.$errors.joinMsg) {
                        _this.joinMsgErrMsg = this.$verify.$errors.joinMsg[0];
                    }
                    return ;
                }
                if (_this.password !== this.passwordConfirmation){
                    _this.passwordConfirmationErrMsg = "确认密码和密码不一致";
                    return ;
                }
                let username = _this.username,
                    email = _this.email,
                    password = _this.password,
                    code = _this.code,
                    signup_message = _this.joinMsg;
                let data = {username, email, password, code, signup_message};
                _this.isBtnSendSignupEmaildisabled = true;
                _this.$store.dispatch('signup', data).then(resp => {
                    if (resp.data.code != 200){
                        Toast(resp.data.message);
                        _this.isBtnSendSignupEmaildisabled = false;
                    }else{
                        if (resp.data.data.is_ok != 1){
                            Toast(resp.data.data.msg);
                            _this.isBtnSendSignupEmaildisabled = false;
                        }else{
                            let username = this.username;
                            let password = this.password;
                            _this.$store.dispatch('login', { username, password }).then(resp => {
                                if (resp.data.code != 200){
                                    Toast(resp.data.message);
                                    _this.isBtnSendSignupEmaildisabled = false;
                                }else{
                                    if (resp.data.data.is_ok != 1){
                                        Toast(resp.data.data.msg);
                                        _this.isBtnSendSignupEmaildisabled = false;
                                    }else{
                                        Toast("注册成功");
                                        _this.$router.push('/');
                                    }
                                }
                            })
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>