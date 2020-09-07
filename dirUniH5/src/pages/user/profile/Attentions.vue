<template>
    <view class="user-profile-attentions container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE_PROFILE_ATTENTIONS" @provider="provider" :pageSize="page_size" color="#66ccff">
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
                                    <u-icon v-if="item.id !== userInfo.id && !item.isYourAttention" @tap="attention(index)" name="eye" size="34" color="" label="关注" class="pull-right text-blue"></u-icon>
                                    <u-icon v-if="item.id !== userInfo.id && item.isYourAttention" @tap="unAttention(index)" name="eye-off" size="34" color="" label="取消关注" class="pull-right text-warning"></u-icon>
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
    import {Toast, Dialog} from 'vant'
    import {mapState} from 'vuex'
    export default {
        name: "UserProfileAttentions",
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
                profileId: "",
                page: 0,
                page_size: 10,
                total: 0
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.profileId){
                Toast("参数profileId传递异常");
                uni.navigateBack();
            }
            _this.profileId = options.profileId;
        },
        mounted() {
            this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.reLoadData()
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize,
                    attentionsForUserId: _this.profileId
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
            },
            attention(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.getItem(index).id
                };
                _this.$auth.post("/user/profile/attention", formParams, false, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.updateItem(index, res.user);
                }, function (msg) {
                    Toast(msg);
                });
            },
            unAttention(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.getItem(index).id
                };
                _this.$auth.post("/user/profile/un-attention", formParams, false, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.updateItem(index, res.user);
                }, function (msg) {
                    Toast(msg);
                });
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.reLoadData();
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_PROFILE_ATTENTIONS.reachBottom();
        }
    }
</script>

<style scoped>

</style>