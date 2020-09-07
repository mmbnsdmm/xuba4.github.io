<template>
    <view class="user-center-my-tag">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li>
                <text class="text-blue" @tap="$router.push('/pages/tag/List')">圈子列表</text>
            </li>
            <li class="active">我的圈子</li>
            <text class="text-blue pull-right" @tap="toCreateCircle">创建圈子</text>
        </ol>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <WLoadMore ref="WODROW_LOAD_MORE_MY_TAG_LIST" @provider="provider" :pageSize="page_size" color="#66ccff">
                        <template v-slot:list="{ items }">
                            <view class="solid-top" v-for="(item, index) in items" :key="index">
                                <u-card padding="10" margin="15rpx" :show-head="false" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx">
                                    <view class="" slot="body">
                                        <view>
                                            <text class="text-blue" style="font-size: 36rpx" v-html="item.name"></text>
                                        </view>
                                    </view>
                                    <view class="" slot="foot">
                                        <u-icon name="eye-fill" size="34" color="" label="查看" class="pull-right text-blue" @tap="toCircle(item.id)"></u-icon>
                                        <u-icon name="close" size="34" color="" label="退出" class="pull-right text-danger" @tap="toQuit(index)"></u-icon>
                                        <div class="clearfix"></div>
                                    </view>
                                </u-card>
                            </view>
                        </template>
                    </WLoadMore>
                </div>
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
        name: "UserCenterMyTag",
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
            this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.reLoadData()
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize,
                    joinUser: _this.userInfo.id
                };
                _this.$auth.post("/tag/default/list", formParams, false, function (r) {
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
            toCreateCircle() {
                let _this = this;
                if (_this.userInfo.email !== _this.$conf.serverData.datas['adminEmail'] && -1 === _this.$conf.serverData.datas['apiAdminUserIds'].indexOf(_this.userInfo.id)) {
                    Toast("你没有权限创建圈子，你可以联系管理员创建需要的圈子");
                }else{
                    uni.navigateTo({
                        url: "/pages/tag/Add"
                    })
                }
            },
            toQuit(index){
                let _this = this;
                Dialog.confirm({
                    title: '确认退出',
                    message: '你确认要退出此圈子吗？'
                }).then(() => {
                    let formParams = {
                        id: _this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.getItem(index).id
                    };
                    _this.$auth.post("/tag/default/quit", formParams, true, function (res) {
                        _this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.reLoadData();
                    }, function (msg) {
                        Toast(msg);
                    });
                }).catch(() => {});
            },
            toCircle(circleId){
                uni.navigateTo({
                    url: "/pages/tag/View?id=" + circleId
                })
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.reLoadData()
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_MY_TAG_LIST.reachBottom()
        }
    }
</script>

<style scoped>

</style>