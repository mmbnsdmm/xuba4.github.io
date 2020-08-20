<template>
    <view class="article-list">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li class="active">文章列表</li>
            <text class="text-blue pull-right" @tap="$router.push('/pages/article/Add')">创建文章</text>
        </ol>
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE_ARTICLE_LIST" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }">
                        <view class="solid-top" v-for="(item, index) in items" :key="index">
                            <u-card padding="10" margin="15rpx" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx"
                                    :title="item.createdBy.nickName" :sub-title="$moment(item.created_at*1000).fromNow()" :thumb="item.createdBy.avatar"
                                    @body-click="toView(item.id, item.isUpdate)" @head-click="toAuthor(item.created_by)">
                                <view class="" slot="body">
                                    <view>
                                        <text class="text-blue" style="font-size: 36rpx" v-html="item.title"></text>
                                        <small class="pull-right text-danger" v-if="item.isUpdate">有更新</small>
                                    </view>
                                    <view>
                                        <u-tag text="标签" mode="light" size="mini" @click.native.stop="toCircle(1)"/>
                                    </view>
                                </view>
                                <view class="" slot="foot">
                                    <text class="text-green" v-if="item.create_type === 1">{{$conf.serverData.enums.article.createTypeDesc[item.create_type]}}</text>
                                    <text class="text-danger" v-if="item.create_type === 2">{{$conf.serverData.enums.article.createTypeDesc[item.create_type]}}</text>
                                    <text class="text-warning" v-if="item.create_type === 3">{{$conf.serverData.enums.article.createTypeDesc[item.create_type]}}</text>
                                    <WI class="single" type="&#xe62b;" font-size="34rpx" v-if="item.created_by !== userInfo.id"></WI>
                                    <u-icon name="star" v-if="item.created_by !== userInfo.id && !item.isYouCollection" :label="item.collectionTotal" @tap="collect(index)"></u-icon>
                                    <u-icon name="star-fill" v-if="item.created_by !== userInfo.id && item.isYouCollection" :label="item.collectionTotal" @tap="unCollect(index)"></u-icon>
                                    <u-icon name="lock-fill" v-if="item.get_password && !item.canView"></u-icon>
                                    <u-icon name="lock-open" v-if="item.get_password && item.canView"></u-icon>
                                    <u-icon name="eye-fill" size="34" color="" label="查看" class="pull-right text-blue" @tap="toView(item.id, item.isUpdate)"></u-icon>
                                    <u-icon name="edit-pen-fill" size="34" color="" label="修改" class="pull-right text-warning" v-if="item.canYouOpt" @tap="toUpdate(item.id, item.isUpdate)"></u-icon>
                                    <u-icon name="close" size="34" color="" label="删除" class="pull-right text-danger" v-if="item.canYouOpt" @tap="toDelete(index)"></u-icon>
                                    <div class="clearfix"></div>
                                </view>
                            </u-card>
                        </view>
                    </template>
                </WLoadMore>
            </div>
        </div>
        <ScrollTopIcon @tapIcon="tapIcon"></ScrollTopIcon>
    </view>
</template>

<script>
    import WLoadMore from '@/plugins/wodrow/list/LoadMore';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    import uniTag from "@/components/uni-tag/uni-tag.vue";
    import WI from '@/plugins/wodrow/iconfont/WI';
    import {mapState} from 'vuex'
    import {Toast, Dialog} from 'vant'
    export default {
        name: "ArticleList",
        components: {
            WLoadMore,
            ScrollTopIcon,
            uniTag,
            WI
        },
        computed: {
            ...mapState(['userInfo'])
        },
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0
            }
        },
        mounted() {
            this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reLoadData()
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize
                };
                _this.$auth.post("/article/default/list", formParams, false, function (r) {
                    _this.$_.forEach(r.list, function (v, k) {
                        v.isUpdate = false;
                        let article = _this.$models.Article.getIsSetById(v.id);
                        if (article && article.updated_at < v.updated_at) {
                            v.isUpdate = true;
                        }
                        if (v.canYouOpt){
                            v.canView = true;
                        }else{
                            if (v.get_password){
                                let password = _this.$auth.getSession("article-get-password-" + v.id);
                                v.canView = password === v.get_password;
                            }else{
                                v.canView = false;
                            }
                        }
                        res.push(v);
                    });
                }, function (msg) {
                    Toast(msg);
                });
                return res;
            },
            tapIcon(e){
                uni.pageScrollTo({
                    duration:60,
                    scrollTop:0
                })
            },
            toView(articleId, isUpdate){
                uni.navigateTo({
                    url: "/pages/article/View?id=" + articleId + "&isLast=" + isUpdate
                });
            },
            toUpdate(articleId, isUpdate){
                uni.navigateTo({
                    url: "/pages/article/Update?id=" + articleId + "&isLast=" + isUpdate
                });
            },
            toDelete(index){
                let _this = this;
                Dialog.confirm({
                    title: '确认删除',
                    message: '你确认要删除此条记录吗？'
                }).then(() => {
                    let formParams = {
                        id: _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(index).id
                    };
                    _this.$auth.post("/article/default/delete", formParams, true, function (res) {
                        _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.deleteItem(index);
                    }, function (msg) {
                        Toast(msg);
                    });
                }).catch(() => {});
            },
            toAuthor(userId){
                uni.navigateTo({
                    url: "/pages/user/profile/Index?id=" + userId
                });
            },
            toCircle(circleId){
                console.log(circleId);
            },
            collect(index){
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(index).id
                };
                _this.$auth.post("/article/default/collection", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            },
            unCollect(index){
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.getItem(index).id
                };
                _this.$auth.post("/article/default/un-collection", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reLoadData()
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_ARTICLE_LIST.reachBottom()
        }
    }
</script>

<style scoped>

</style>