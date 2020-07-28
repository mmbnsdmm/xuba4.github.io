<template>
    <div class="login">
        <van-panel title="" desc="用户登录" status="">
            <van-cell-group>
                <van-field v-model="username" required placeholder="请输入用户名" />
                <van-field v-model="password" required type="password" placeholder="请输入密码" />
            </van-cell-group>
            <div slot="footer">
                <van-button size="large" type="info" @click="login" :disabled="isLoginBtnDisabled">登录</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "Login",
        data(){
            return {
                username : "",
                password : "",
                isLoginBtnDisabled: false
            }
        },
        methods: {
            login: function () {
                let _this = this;
                let username = _this.username;
                let password = _this.password;
                _this.isLoginBtnDisabled = true;
                console.log(login)
            }
        }
    }
</script>

<style lang="stylus">

</style>