<template>
    <view class="article-view">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li>
                <text class="text-blue" @tap="$router.push('/pages/article/List')">文章列表</text>
            </li>
            <li class="active">{{article.title}}</li>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h4>{{article.title}} <WI class="single pull-right" type="&#xe621;" font-size="44rpx" title="配置" @click.native="actionSheetShow= ! actionSheetShow"></WI></h4>
                    <small>发布者:<code>{{article.createdBy.nickName}}</code>|发布时间:<code>{{$moment(article.created_at*1000).fromNow()}}</code>|最近更新:<code>{{$moment(article.updated_at*1000).fromNow()}}</code></small>
                    <hr>
                    <view class="u-content">
                        <u-parse :html="article.content"></u-parse>
                    </view>
                </div>
            </div>
        </div>
        <ScrollTopIcon @tapIcon="tapIcon"></ScrollTopIcon>
        <van-action-sheet v-model="actionSheetShow" :actions="actionSheetList" @select="sheetListSelect" />
    </view>
</template>

<script>
    import {Toast} from 'vant';
    import WI from '@/plugins/wodrow/iconfont/WI';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    export default {
        name: "ArticleView",
        components: {
            WI,
            ScrollTopIcon
        },
        data() {
            return {
                article: {
                    id: null,
                    created_by: null,
                    created_at: null,
                    updated_by: null,
                    updated_at: null,
                    content: null,
                    get_password: null,
                    is_boutique: 0,
                    min_integral: 0,
                    min_level: 0,
                    title: null,
                    status: null,
                    createdBy: {}
                },
                actionSheetList: [],
                actionSheetShow: false
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.id){
                Toast("参数id传递异常");
                uni.navigateBack();
            }
            _this.$set(_this.$data, "article", _this.$models.Article.getById(options.id, options.isLast));
            _this.actionSheetList.push({
                name: "修改",
                action: "update"
            });
        },
        mounted() {
            let _this = this;
            _this.actionSheetList.push({
                name: "新建",
                action: "add"
            })
        },
        methods: {
            sheetListSelect(item) {
                let _this = this;
                switch (item.action){
                    case "add":
                        uni.navigateTo({
                            url: "/pages/article/Add"
                        });
                        break;
                    case "update":
                        uni.navigateTo({
                            url: "/pages/article/Update?id=" + _this.article.id
                        });
                        break;
                }
            },
            tapIcon(e){
                uni.pageScrollTo({
                    duration:60,
                    scrollTop:0
                })
            }
        },
        onPullDownRefresh(){
            let _this = this;
            _this.$set(_this.$data, "article", _this.$models.Article.getById(_this.article.id, true));
            setTimeout(function () {
                uni.stopPullDownRefresh();
            }, 1000);
        },
        onReachBottom() {
            console.log("onReachBottom");
        }
    }
</script>

<style scoped>

</style>