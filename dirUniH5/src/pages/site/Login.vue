<template>
    <div class="site-login">
        <div class="container">
            <div class="col-row">
                <div class="col-xs-12">
                    <form>
                        <h4>用户登录</h4>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">File input</label>
                            <input type="file" id="exampleInputFile">
                            <p class="help-block">Example block-level help text here.</p>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Check me out
                            </label>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    import { mapState, mapMutations } from 'vuex'
    export default {
        name: "SiteLogin",
        data(){
            return {
                username : "",
                password : "",
                isLoginBtnDisabled: false
            }
        },
        methods: {
            ...mapMutations(['login']),
            toLogin: function () {
                let _this = this;
                let username = _this.username;
                let password = _this.password;
                // _this.isLoginBtnDisabled = true;
                _this.$http.post("/site/login", {username: username, password: password}, true, function (res) {
                    _this.login(res.user);
                    if (!_this.hasLogin){
                        Toast("登陆失败，请联系管理员")
                    } else {
                        if (_this.$tool.getCache('beforeLoginPath')){
                            _this.$router.push(_this.$tool.getCache('beforeLoginPath'));
                        } else{
                            _this.$router.go(-1);
                        }
                    }
                }, function () {
                    Toast("用户名或密码错误")
                });
            }
        },
        computed: {
            ...mapState(['hasLogin'])
        },
        mounted: function () {
            let _this = this;
            console.log(_this.$router.beforeHooks.from);
        }
    }
</script>

<style>
     uni-page-wrapper {
        background: #ffffff;
     }
</style>