<template>
    <div class="home">
        <van-notice-bar :text="this.$conf.data.lastVueApp.noticeMsg" v-if="!!this.$conf.data.lastVueApp.noticeMsg" />
        <h2 class="block-title" v-if="false">使用方式</h2>
        <van-divider dashed v-if="false">使用方式</van-divider>
        <van-collapse v-model="activeName" accordion>
            <van-collapse-item title="系统信息" name="1">
                <p v-for="item in this.$conf.data.datas.sysInfo" :key="item.i">
                    {{item.k}}:{{item.v}}
                </p>
            </van-collapse-item>
            <van-collapse-item title="使用方法" name="2">
                <p v-for="item in this.$conf.data.datas.howToUse" :key="item.i">
                    {{item.k}}:{{item.v}}
                </p>
            </van-collapse-item>
            <van-collapse-item title="注意事项" name="3">
                <p v-for="item in this.$conf.data.datas.warnings" :key="item.i">
                    {{item.k}}:{{item.v}}
                </p>
            </van-collapse-item>
            <van-collapse-item title="QQ群" name="4">
                <p>请加一个qq群</p>
                <p v-for="item in this.$conf.data.datas.qqqs" :key="item.qqq">
                    {{item.qqq}}
                    <van-tag :type="item.isFull === true?'default':'primary'">{{item.isFull === true?"已满":"未满"}}</van-tag>
                    <van-tag :type="item.canJoin === true?'primary':'default'">{{item.canJoin === true?"可加入":"不可加入"}}</van-tag>
                    加入认证码: <code>{{item.checkCode}}</code>
                </p>
            </van-collapse-item>
            <van-collapse-item title="下载链接" name="5">
                <p>安卓:<code>{{this.$conf.data.lastVueApp.androidAppDownloadUrl}}</code></p>
                <p>ios(需要越狱):<code>{{this.$conf.data.lastVueApp.iosAppDownloadUrl}}</code></p>
                <p>web版(最新):<code>{{this.$conf.data.lastVueApp.webAppUrl}}</code></p>
            </van-collapse-item>
        </van-collapse>
    </div>
</template>

<script>
    // @ is an alias to /src

    export default {
        name: 'home',
        mounted: function() {},
        data() {
            return {
                activeName: '3'
            };
        }
    }
</script>
