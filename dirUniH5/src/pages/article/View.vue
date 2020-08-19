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
        <div class="validate-password" v-if="!canView">
            <view class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h4>验证密码</h4>
                        <div class="form-group">
                            <label>文章浏览密码</label>
                            <input type="password" class="form-control" v-model="password" placeholder="密码">
                        </div>
                        <button class="btn btn-primary btn-block" :disabled="isBtnDisabled" @tap="toValidatePassword">确认</button>
                    </div>
                </div>
            </view>
        </div>
        <div class="container" v-if="canView">
            <div class="row">
                <div class="col-xs-12">
                    <h4>
                        {{article.title}}
                        <WI class="single pull-right" type="&#xe621;" font-size="35rpx" title="配置" @click.native="actionSheetShow= ! actionSheetShow"></WI>
                        <u-icon name="star" class="pull-right" v-if="article.created_by !== userInfo.id && !article.isYouCollection" @tap="collect"></u-icon>
                        <u-icon name="star-fill" class="pull-right" v-if="article.created_by !== userInfo.id && article.isYouCollection" @tap="unCollect"></u-icon>
                    </h4>
                    <small><code>{{$conf.serverData.enums.article.createTypeDesc[article.create_type]}}</code>|发布者:<code>{{article.createdBy.nickName}}</code>|发布时间:<code>{{$moment(article.created_at*1000).fromNow()}}</code>|最近更新:<code>{{$moment(article.updated_at*1000).fromNow()}}</code></small>
                    <hr>
                    <view class="u-content">
                        <u-parse :html="article.content" :lazy-load="true" loading-img="@/static/loading1.jpg"></u-parse>
                    </view>
                </div>
            </div>
        </div>
        <ScrollTopIcon @tapIcon="tapIcon"></ScrollTopIcon>
        <van-action-sheet v-model="actionSheetShow" :actions="actionSheetList" @select="sheetListSelect" />
    </view>
</template>

<script>
    import {Toast, Dialog} from 'vant';
    import WI from '@/plugins/wodrow/iconfont/WI';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    import {mapState} from 'vuex';
    export default {
        name: "ArticleView",
        components: {
            WI,
            ScrollTopIcon
        },
        computed: {
            ...mapState(['userInfo'])
        },
        data() {
            return {
                password: "",
                isBtnDisabled: false,
                canView: false,
                article: {},
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
            let article = _this.$models.Article.getById(options.id, options.isLast);
            if (!article.id) {
                uni.reLaunch({
                    url: "/pages/article/List"
                });
            }else{
                _this.$set(_this.$data, "article", article);
                if (article.canYouOpt) {
                    _this.actionSheetList.push({
                        name: "修改",
                        action: "update"
                    });
                    _this.actionSheetList.push({
                        name: "删除",
                        action: "delete"
                    });
                    _this.canView = true;
                }else{
                    if (_this.article.get_password){
                        let password = _this.$auth.getSession("article-get-password-" + _this.article.id);
                        _this.canView = password === _this.article.get_password;
                    } else {
                        _this.canView = true;
                    }
                }
            }
        },
        mounted() {
            let _this = this;
            _this.actionSheetList.push({
                name: "新建",
                action: "add"
            })
        },
        methods: {
            toValidatePassword() {
                let _this = this;
                if (_this.article.get_password === _this.password){
                    _this.$auth.setSession("article-get-password-" + _this.article.id, _this.password);
                    _this.canView = true;
                } else {
                    Toast("密码不正确，你可以联系文章发布者");
                    _this.canView = false;
                }
            },
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
                    case "delete":
                        Dialog.confirm({
                            title: '确认删除',
                            message: '你确认要删除此条记录吗？'
                        }).then(() => {
                            let formParams = {id: _this.article.id};
                            _this.$auth.post("/article/default/delete", formParams, true, function (res) {
                                uni.reLaunch({
                                    url: "/pages/article/List"
                                });
                            }, function (msg) {
                                Toast(msg);
                            });
                        }).catch(() => {});
                        break;
                }
            },
            tapIcon(e){
                uni.pageScrollTo({
                    duration:60,
                    scrollTop:0
                })
            },
            collect(){
                let _this = this;
                let formParams = {
                    id: _this.article.id
                };
                _this.$auth.post("/article/default/collection", formParams, true, function (res) {
                    _this.$set(_this.article, "isYouCollection", res.info.isYouCollection);
                }, function (msg) {
                    Toast(msg);
                });
            },
            unCollect(){
                let _this = this;
                let formParams = {
                    id: _this.article.id
                };
                _this.$auth.post("/article/default/un-collection", formParams, true, function (res) {
                    _this.$set(_this.article, "isYouCollection", res.info.isYouCollection);
                }, function (msg) {
                    Toast(msg);
                });
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