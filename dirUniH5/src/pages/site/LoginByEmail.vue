<template>
    <div class="site-login-by-email">
        <view class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h4>邮箱登录</h4>
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
                            <button class="btn btn-warning btn-block" :disabled="isBtnSendVerifyCodedisabled" @tap="sendVerifyCode" v-preventReClick>发送验证码{{countDownSendCode}}</button>
                        </div>
                    </div>
                    <div class="help-block">
                        <navigator url="/pages/site/Login" class="float-left">
                            <text class="text-blue">用户名登陆</text>
                        </navigator>
                        <text :decode="false" class="float-left">&nbsp; | &nbsp;</text>
                        <navigator url="/pages/site/Signup" class="float-left">
                            <text class="text-blue">注册</text>
                        </navigator>
                        <navigator url="/pages/site/ResetPassword" class="float-right">
                            <text class="text-blue">忘记密码</text>
                        </navigator>
                        <text :decode="false" class="float-right">&nbsp; | &nbsp;</text>
                        <text class="text-blue float-right" @tap="$router.push('/')">首页</text>
                        <div class="clearfix"></div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg" :disabled="isBtnDisabled" @tap="toLoginByEmail">登录</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant';
    import {mapState, mapMutations} from 'vuex'
    export default {
        name: "SiteLoginByEmail",
        data(){
            return {
                email : "",
                emailVerifyCode : "",
                countDownSendCode: "",
                isBtnSendVerifyCodedisabled: false,
                isBtnDisabled: false
            }
        },
        computed: {
            ...mapState(['hasLogin'])
        },
        mounted: function() {},
        methods: {
            ...mapMutations(['login']),
            sendVerifyCode: function() {
                let _this = this;
                if (!/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(_this.email)){
                    Toast("邮箱格式不正确");
                    return ;
                }
                _this.$http.post('/site/send-email-code', {
                    email: this.email,
                    typeKey: 2
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
            toLoginByEmail: function () {
                let _this = this;
                if (!/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(_this.email)){
                    Toast("邮箱格式不正确");
                    return ;
                }
                if (!_this.emailVerifyCode){
                    Toast("验证码不能为空");
                    return;
                }
                _this.isBtnDisabled = true;
                _this.$http.post('/site/login-by-email', {
                    email: _this.email,
                    code: _this.emailVerifyCode
                }, true, function (res) {
                    Toast(res.msg);
                    _this.login(res.user);
                    if (!_this.hasLogin){
                        Toast("登陆失败，请联系管理员");
                        _this.isBtnDisabled = false;
                    } else {
                        if (_this.$tool.getCache('beforeLoginPath')){
                            _this.$router.push(_this.$tool.getCache('beforeLoginPath'));
                        } else{
                            _this.$router.go(-1);
                        }
                    }
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