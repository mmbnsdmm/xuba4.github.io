<template>
    <div class="home">
        <van-notice-bar :text="this.$conf.data.versionUpdate.noticeMsg" v-if="!!this.$conf.data.versionUpdate.noticeMsg" />
        <h2 class="block-title" v-if="false">使用方式</h2>
        <van-divider dashed v-if="false">使用方式</van-divider>
        <van-collapse v-model="activeName" accordion>
            <van-collapse-item title="系统信息" name="1">
                <p>服务器剩余容量: {{this.$conf.data.apiServer.diskFreeSpace}}</p>
            </van-collapse-item>
            <van-collapse-item title="使用方法" name="2">
                <p>注册或登录</p>
                <p>创建或加入圈子</p>
                <p>进入个人中心进行入门验证，一般一周内会有回复</p>
                <p>浏览或发布文章</p>
            </van-collapse-item>
            <van-collapse-item title="注意事项" name="3">
                <p>由于当前版本和老版本用的是同一个服务器，老版本比较吃资源，所以速度比较慢</p>
                <p>新版本安装后请进入个人中心清除缓存</p>
                <p>请不要频繁清除缓存</p>
                <p>禁止发布水文以及其他广告，发现后删除用户</p>
                <p>不要创建无意义的圈子，发现后删除用户</p>
                <p>因水文以及其他无意义的文章太多，之前版本的文章本人近期会进行筛选后发布</p>
                <p>你只能查看你发布的文章和你所加入圈子的文章</p>
            </van-collapse-item>
            <van-collapse-item title="QQ群" name="4">
                <p>请加一个q群</p>
                <p v-for="item in this.$conf.data.apiServer.qqqs" :key="item.qqq">
                    {{item.qqq}}
                    <van-tag :type="item.isFull === true?'default':'primary'">{{item.isFull === true?"已满":"未满"}}</van-tag>
                    <van-tag :type="item.canJoin === true?'primary':'default'">{{item.canJoin === true?"可加入":"不可加入"}}</van-tag>
                    加入认证码: <code>{{item.checkCode}}</code>
                </p>
            </van-collapse-item>
            <van-collapse-item title="下载链接" name="5">
                <p>安卓:<code>{{this.$conf.data.versionUpdate.androidAppDownloadUrl}}</code></p>
                <p>ios(需要越狱):<code>{{this.$conf.data.versionUpdate.iosAppDownloadUrl}}</code></p>
                <p>web版(最新):<code>{{this.$conf.data.versionUpdate.webAppUrl}}</code></p>
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
