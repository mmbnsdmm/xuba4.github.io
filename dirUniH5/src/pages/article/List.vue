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
                <WLoadMore ref="WODROW_LOAD_MORE" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }">
                        <view class="solid-top" v-for="(item, index) in items" :key="index">
                            <u-card padding="10" margin="15rpx" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx"
                                    :title="item.createdBy.nickName" :sub-title="$moment(item.created_at*1000).fromNow()" :thumb="item.createdBy.avatar"
                                    @body-click="toView(item.id, item.isUpdate)" @head-click="toAuthor(item.created_by)">
                                <view class="" slot="body">
                                    <view>
                                        <text class="text-blue">{{item.title}}</text>
                                        <small class="pull-right text-danger" v-if="item.isUpdate">有更新</small>
                                    </view>
                                    <view>
                                        <u-tag text="标签" mode="light" size="mini" @click.native.stop="toCircle(1)"/>
                                    </view>
                                </view>
                                <view class="" slot="foot">
                                    <u-icon name="eye-fill" size="34" color="" label="查看" class="pull-right" @tap="toView(item.id, item.isUpdate)"></u-icon>
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
    import uniTag from "@/components/uni-tag/uni-tag.vue"
    import {Toast, Dialog} from 'vant'
    export default {
        name: "ArticleList",
        components: {
            WLoadMore,
            ScrollTopIcon,
            uniTag
        },
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0,
                list: []
            }
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE.pushData(res);
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
            toAuthor(userId){
                uni.navigateTo({
                    url: "/pages/user/profile/Index?id=" + userId
                });
            },
            toCircle(circleId){
                console.log(circleId);
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE.reLoadData()
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE.reachBottom()
        }
    }
</script>

<style scoped>

</style>