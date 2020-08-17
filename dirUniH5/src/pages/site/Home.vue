<template>
    <div class="home">
        <u-grid :col="3">
            <u-grid-item @tap="toScan">
                <u-icon name="scan" :size="46"></u-icon>
                <view class="grid-text">扫一扫</view>
            </u-grid-item>
            <u-grid-item @tap="toPublish">
                <u-icon name="edit-pen" :size="46"></u-icon>
                <view class="grid-text">发布</view>
            </u-grid-item>
            <u-grid-item @tap="toSearch">
                <u-icon name="search" :size="46"></u-icon>
                <view class="grid-text">搜索</view>
            </u-grid-item>
        </u-grid>
        <uni-collapse accordion="true">
            <uni-collapse-item title="项目介绍">
                <view style="padding: 30rpx;">
                    <p>本系统完全免费，致力于一个安全的交流分享平台</p>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="系统信息">
                <view style="padding: 30rpx;">
                    <p>
                        前端使用了h5开发，如果需要支持App需要做兼容处理，使用的前端技术主要有<code>vuecli</code> <code>uniapp</code> <code>vuex</code> <code>vant</code> <code>uview</code> <code>git pages</code>，后端使用了<code>PHP</code>的<code>Yii2.0</code>框架开发，系统环境为<code>centos7</code>，使用了宝塔面板快速配置了api运行环境，数据库使用了<code>mysql7</code>
                    </p>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="QQ群">
                <view style="padding: 30rpx;">
                    <p v-for="item in qqqs" :key="item.k">
                        {{item.qqq}}
                        <van-tag :type="item.isFull === true?'default':'primary'">{{item.isFull === true?"已满":"未满"}}</van-tag>
                        <van-tag :type="item.canJoin === true?'primary':'default'">{{item.canJoin === true?"可加入":"不可加入"}}</van-tag>
                        加入认证码: <code>{{item.checkCode}}</code>
                    </p>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="联系方式">
                <view style="padding: 30rpx;">
                    <p>管理员邮箱: <code>{{adminEmail}}</code></p>
                    <p>管理员QQ: <code>{{adminQQ}}</code></p>
                    <p>管理员微信: <code>{{adminWX}}</code></p>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="注意事项">
                <view style="padding: 30rpx;">
                    <p>你可以使用网页端，地址为 <u-link :href="$conf.appUrl">{{$conf.appUrl}}</u-link></p>
                </view>
            </uni-collapse-item>
        </uni-collapse>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    import uniCollapse from '@/components/uni-collapse/uni-collapse.vue'
    import uniCollapseItem from '@/components/uni-collapse-item/uni-collapse-item.vue'
    export default {
        components: {uniCollapse,uniCollapseItem},
        name: 'SiteHome',
        data() {
            return {
                qqqs: [],
                adminEmail: "",
                adminQQ: "",
                adminWX: ""
            };
        },
        mounted: function() {
            let _this = this;
            _this.$http.post('/public/get-datas', {}, true, function (res) {
                let qqqs = res.datas.qqqs;
                let x = [1, 2, 3];
                _this.$tool.vueArrayReset(_this.qqqs, qqqs);
                _this.adminEmail = res.datas.adminEmail;
                _this.adminQQ = res.datas.adminQQ;
                _this.adminWX = res.datas.adminWX;
            }, function (msg) {
                Toast(msg);
                return ;
            });
        },
        methods: {
            toScan: function () {
                Toast("该功能暂未实现");
            },
            toPublish: function () {
                uni.navigateTo({
                    url: "/pages/article/Add"
                });
                // let _this = this;
                // _this.$router.push('/pages/article/Add')
            },
            toSearch: function () {
                uni.navigateTo({
                    url: "/pages/search/Search"
                });
            }
        }
    }
</script>

<style scoped>

</style>
