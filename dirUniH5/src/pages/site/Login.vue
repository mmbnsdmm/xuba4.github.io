<template>
    <div class="login">
        <van-panel title="" desc="用户登录" status="">
            <van-cell-group>
                <van-field v-model="username" required placeholder="请输入用户名" />
                <van-field v-model="password" required type="password" placeholder="请输入密码" />
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="toLogin" :disabled="isLoginBtnDisabled">登录</van-button>
            </div>
        </van-panel>
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
                    if (_this.$tool.getCache('beforeLoginPath')){
                        _this.$router.push(_this.$tool.getCache('beforeLoginPath'));
                    } else{
                        _this.$router.go(-1);
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

<style lang="stylus">

</style>