<template>
    <div class="site-reset-password">
        <view class="content">
            <div class="col-row">
                <div class="col-xs-12">
                    <h4>重置密码</h4>
                    <div class="form-group">
                        <label>邮箱</label>
                        <input type="email" class="form-control" v-model="email" placeholder="请输入邮箱" required>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="emailVerifyCode" placeholder="请输入验证码" required>
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <button class="btn btn-warning btn-block" :disabled="isBtnSendVerifyCodedisabled" @click="sendVerifyCode" v-preventReClick>发送验证码{{countDownSendCode}}</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>密码</label>
                        <input type="password" class="form-control" v-model="password" placeholder="请输入密码">
                    </div>
                    <div class="form-group">
                        <label>确认密码</label>
                        <input type="password" class="form-control" v-model="passwordConfirmation" placeholder="再次确认密码">
                    </div>
                    <div class="help-block">
                        <navigator url="/pages/site/Login" class="float-left">
                            <text class="text-blue">用户名登陆</text>
                        </navigator>
                        <text :decode="false" class="float-left">&nbsp; | &nbsp;</text>
                        <navigator url="/pages/site/LoginByEmail" class="float-left">
                            <text class="text-blue">邮箱登陆</text>
                        </navigator>
                        <navigator url="/pages/site/Signup" class="float-right">
                            <text class="text-blue">注册</text>
                        </navigator>
                        <text :decode="false" class="float-right">&nbsp; | &nbsp;</text>
                        <text class="text-blue float-right" @click="$router.push('/')">首页</text>
                        <div class="clearfix"></div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg" :disabled="isBtnDisabled" @click="toResetPassword" v-preventReClick>重置</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant';
    export default {
        name: "SiteResetPassword",
        data(){
            return {
                email : "",
                emailVerifyCode : "",
                countDownSendCode: "",
                password : "",
                passwordConfirmation : "",
                isBtnSendVerifyCodedisabled: false,
                isBtnDisabled: false
            }
        },
        mounted: function() {},
        methods: {
            sendVerifyCode: function() {
                let _this = this;
                if (!/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(_this.email)){
                    Toast("邮箱格式不正确");
                    return ;
                }
                _this.$http.post('/site/send-email-code', {
                    email: this.email,
                    typeKey: 3
                }, true, function (res) {
                    Toast(res.msg);
                    _this.countDownSendCode = 120;
                    _this.isBtnSendVerifyCodedisabled = true;
                    let timerSendCode = setInterval(() => {
                        _this.countDownSendCode--;
                        if (_this.countDownSendCode <= 0) {
                            _this.countDownSendCode = '';
                            _this.isBtnSendVerifyCodedisabled = false;
                            clearInterval(timerSendCode);
                        }
                    }, 1000);
                }, function (msg) {
                    Toast(msg);
                    _this.isBtnDisabled = false;
                    return ;
                })
            },
            toResetPassword: function () {
                let _this = this;
                if (!/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(_this.email)){
                    Toast("邮箱格式不正确");
                    return ;
                }
                if (!_this.emailVerifyCode){
                    Toast("验证码不能为空");
                    return;
                }
                if (!_this.password){
                    Toast("密码不能为空");
                    return;
                }
                if (_this.password.length < 6){
                    Toast("密码至少6位");
                    return ;
                }
                if (!_this.passwordConfirmation){
                    Toast("确认密码不能为空");
                    return;
                }
                if (_this.passwordConfirmation !== _this.password){
                    Toast("确认密码必须和密码一致");
                    return;
                }
                _this.isBtnDisabled = true;
                _this.$http.post('/site/reset-password', {
                    email: _this.email,
                    code: _this.emailVerifyCode,
                    password: _this.password
                }, true, function (res) {
                    Toast(res.msg);
                    _this.$tool.setCache('beforeLoginPath', "/");
                    _this.$router.push("/pages/site/Login");
                    _this.isBtnDisabled = false;
                }, function (msg) {
                    Toast(msg);
                    _this.isBtnDisabled = false;
                    return ;
                })
            }
        }
    }
</script>

<style scoped>

</style>