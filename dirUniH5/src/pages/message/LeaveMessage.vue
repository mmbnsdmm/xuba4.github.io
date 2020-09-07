<template>
    <view class="message-leave-message container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE_COMMENT_LIST" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }">
                        <view class="solid-top" v-for="(item, index) in items" :key="index">
                            <u-card :title="item.createdBy.nickName" :sub-title="$moment(item.created_at*1000).fromNow()" :thumb="item.createdBy.avatar"
                                    padding="10" margin="15rpx" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx"
                                    @head-click="toAuthor(item.created_by)">
                                <view class="" slot="body">
                                    <view>
                                        <text v-html="item.message"></text>
                                    </view>
                                </view>
                                <view class="" slot="foot">
                                    <text class="text-gray">#{{item.id}}</text>
                                    <WI class="single" type="&#xe62b;" font-size="34rpx"></WI>
                                    <u-icon name="thumb-up" size="34" color="" :label="item.praiseTotal" @tap="praise(index)" v-if="!item.isYouPraise"></u-icon>
                                    <u-icon name="thumb-up-fill" size="34" color="" :label="item.praiseTotal" @tap="unPraise(index)" v-if="item.isYouPraise"></u-icon>
                                    <u-icon name="thumb-down" size="34" color="" :label="item.trampleTotal" @tap="trample(index)" v-if="!item.isYouTrample"></u-icon>
                                    <u-icon name="thumb-down-fill" size="34" color="" :label="item.trampleTotal" @tap="unTrample(index)" v-if="item.isYouTrample"></u-icon>
                                    <u-icon name="chat" size="34" color="" label="回复" class="pull-right text-blue" v-if="item.created_by !== userInfo.id" @tap="replyComment(index)">></u-icon>
                                    <u-icon name="edit-pen-fill" size="34" color="" label="修改" class="pull-right text-warning" v-if="item.canYouOpt" @tap="updateComment(index)"></u-icon>
                                    <u-icon name="close" size="34" color="" label="删除" class="pull-right text-danger" v-if="item.canYouOpt" @tap="deleteComment(index)"></u-icon>
                                    <div class="clearfix"></div>
                                </view>
                            </u-card>
                        </view>
                    </template>
                </WLoadMore>
            </div>
        </div>
        <view class="comment-send-bottom">
            <view class="start-comment-view" @tap="startComment">
                <view class="textarea-comment">写留言...</view>
            </view>
        </view>
        <WComment ref="WODROW_COMMENT" placeholder="留言内容至少10个字符，最多500个字符" :minStrLen="10" :maxStrLen="500" @publishComment="publishComment"></WComment>
        <ScrollTopIcon @tapIcon="tapIcon"></ScrollTopIcon>
    </view>
</template>

<script>
    import {Toast, Dialog} from 'vant'
    import WLoadMore from '@/plugins/wodrow/list/LoadMore';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    import WComment from "@/plugins/wodrow/message/Comment";
    import WI from '@/plugins/wodrow/iconfont/WI';
    import {mapState} from 'vuex';
    export default {
        name: "MessageLeaveMessage",
        components: {
            WLoadMore,
            ScrollTopIcon,
            WComment,
            WI
        },
        computed: {
            ...mapState(['userInfo'])
        },
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0,
                updateCommentIndex: null
            }
        },
        mounted() {
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData()
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize
                };
                _this.$auth.post("/message/leave/list", formParams, false, function (r) {
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
            startComment() {
                let _this = this;
                _this.$refs.WODROW_COMMENT.toggleMask('show');
                _this.updateCommentIndex = null;
                _this.$refs.WODROW_COMMENT.content = "";
            },
            updateComment(index) {
                let _this = this;
                _this.startComment();
                _this.updateCommentIndex = index;
                _this.$refs.WODROW_COMMENT.content = _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(_this.updateCommentIndex).message;
            },
            deleteComment(index) {
                let _this = this;
                Dialog.confirm({
                    title: '确认删除',
                    message: '你确认要删除此条记录吗？'
                }).then(() => {
                    let formParams = {
                        id: _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id
                    };
                    _this.$auth.post("/message/leave/delete", formParams, true, function (res) {
                        _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData();
                    }, function (msg) {
                        Toast(msg);
                    });
                }).catch(() => {});
            },
            replyComment(index) {
                let _this = this;
                _this.startComment();
                _this.$refs.WODROW_COMMENT.content = "<code>@#" + _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id + "</code>";
            },
            publishComment(content) {
                let _this = this;
                let formParams = {
                    message: content,
                };
                if (_this.updateCommentIndex !== null) formParams.id = _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(_this.updateCommentIndex).id;
                _this.$auth.post("/message/leave/publish", formParams, true, function (res) {
                    _this.$refs.WODROW_COMMENT.toggleMask();
                    _this.$refs.WODROW_COMMENT.content = "";
                    if (_this.updateCommentIndex === null){
                        _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData();
                    }else{
                        _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.updateItem(_this.updateCommentIndex, res.info);
                    }
                }, function (msg) {
                    Toast(msg);
                });
            },
            praise(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id
                };
                _this.$auth.post("/message/leave/praise", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            },
            unPraise(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id
                };
                _this.$auth.post("/message/leave/un-praise", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            },
            trample(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id
                };
                _this.$auth.post("/message/leave/trample", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            },
            unTrample(index) {
                let _this = this;
                let formParams = {
                    id: _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.getItem(index).id
                };
                _this.$auth.post("/message/leave/un-trample", formParams, true, function (res) {
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.updateItem(index, res.info);
                }, function (msg) {
                    Toast(msg);
                });
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData();
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reachBottom();
        }
    }
</script>

<style lang="scss" scoped>
    .message-leave-message{
        margin-bottom: 50px;
    }
    .comment-send-bottom {
        width: 100%;
        position: fixed;
        bottom: 0;
        display: flex;
        flex-direction: row;
        background-color: white;
        border-top: 1px solid #fbfbfb;
    }
    .start-comment-view {
        background-color: $uni-bg-color-grey;
        border-radius: 40rpx;
        padding: 10rpx 20rpx;
        margin: 25rpx 20rpx 25rpx 20rpx;
        width: 80%;
        flex: 1;
        align-items: center;
    }
</style>