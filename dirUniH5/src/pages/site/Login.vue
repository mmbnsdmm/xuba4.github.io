<template>
    <div class="site-login">
        <view class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h4>用户登录</h4>
                    <div class="form-group">
                        <label>用户名</label>
                        <input type="text" class="form-control" v-model="username" placeholder="请输入用户名" required>
                    </div>
                    <div class="form-group">
                        <label>密码</label>
                        <input type="password" class="form-control" v-model="password" placeholder="请输入密码">
                    </div>
                    <div class="help-block">
                        <navigator url="/pages/site/LoginByEmail" class="float-left">
                            <text class="text-blue">邮箱登陆</text>
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
                    <button class="btn btn-primary btn-block btn-lg" :disabled="isBtnDisabled" @tap="toLogin">登录</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    import {mapState, mapMutations} from 'vuex'
    export default {
        name: "SiteLogin",
        data(){
            return {
                username : "",
                password : "",
                isBtnDisabled: false
            }
        },
        methods: {
            ...mapMutations(['login']),
            toLogin: function () {
                let _this = this;
                if (_this.username.length < 6){
                    Toast("用户名至少6位");
                    return;
                }
                _this.isBtnDisabled = true;
                _this.$http.post("/site/login", {username: _this.username, password: _this.password}, true, function (res) {
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
                });
            }
        },
        computed: {
            ...mapState(['hasLogin'])
        },
        mounted: function () {
            let _this = this;
        }
    }
</script>

<style>
</style>