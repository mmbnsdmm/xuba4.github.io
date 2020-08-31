<template>
    <view class="user-center-my-fans">
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE_MY_FANSES" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }">
                        <view class="solid-top" v-for="(item, index) in items" :key="index">
                            <u-card padding="10" margin="15rpx" :show-foot="false" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx"
                                    :title="item.nickName" :thumb="item.avatar"
                                    @head-click="toAuthor(item.id)">
                                <view class="" slot="body">
                                    <text>关注:<code>{{item.attentionTotal}}</code></text>
                                    <text>粉丝:<code>{{item.fansTotal}}</code></text>
                                    <text>文章:<code>{{item.articleTotal}}</code></text>
                                    <text>收藏:<code>{{item.collectionTotal}}</code></text>
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
    import {Toast, Dialog} from 'vant';
    import {mapState} from 'vuex';
    export default {
        name: "UserCenterMyFans",
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
            this.$refs.WODROW_LOAD_MORE_MY_FANSES.reLoadData()
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_MY_FANSES.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize,
                    fansesForUserId: _this.userInfo.id
                };
                _this.$auth.post("/user/profile/list", formParams, false, function (r) {
                    _this.$_.forEach(r.list, function (v, k) {
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
            toAuthor(userId){
                uni.navigateTo({
                    url: "/pages/user/profile/Index?id=" + userId
                });
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_MY_FANSES.reLoadData();
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_MY_FANSES.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_MY_FANSES.reachBottom();
        }
    }
</script>

<style scoped>

</style>