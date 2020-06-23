<template>
    <div id="app">
        <van-nav-bar :title="$route.name">
            <van-icon name="arrow-right" slot="right"/>
        </van-nav-bar>
        <router-view></router-view>
        <van-tabbar :z-index="110" route>
            <van-tabbar-item replace to="/login" icon="home-o" v-if="!this.$store.getters.isAuth">登录</van-tabbar-item>
            <van-tabbar-item replace to="/login-by-email" icon="home-o" v-if="!this.$store.getters.isAuth">邮箱登录</van-tabbar-item>
            <van-tabbar-item replace to="/signup" icon="home-o" v-if="!this.$store.getters.isAuth">注册</van-tabbar-item>
            <van-tabbar-item replace to="/reset-password" icon="home-o" v-if="!this.$store.getters.isAuth">重置密码</van-tabbar-item>
            <van-tabbar-item replace to="/" icon="home-o" v-if="this.$store.getters.isAuth">主页</van-tabbar-item>
            <van-tabbar-item replace to="/user/center" icon="manager-o" v-if="this.$store.getters.isAuth">个人中心</van-tabbar-item>
        </van-tabbar>
    </div>
</template>

<script>
    export default {
        computed: {},
        data() {
            return {}
        },
        methods: {},
        mounted: function () {}
    }
</script>

<style lang="stylus">
    body
        background #f8f8f8 !important

    div
        word-wrap:break-word;
        word-break:break-all;
        overflow: hidden;

    img
        max-width 100%

    #app
        font-family 'Avenir', Helvetica, Arial, sans-serif
        -webkit-font-smoothing antialiased
        -moz-osx-font-smoothing grayscale
        padding-bottom 46px

    .block-title {
        margin: 0;
        padding: 16px 16px 16px 16px;
        color: rgba(69, 90, 100, 0.6);
        font-weight: normal;
        font-size: 14px;
    }
</style>
