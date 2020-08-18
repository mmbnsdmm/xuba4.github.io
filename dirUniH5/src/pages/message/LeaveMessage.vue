<template>
    <view class="message-leave-message">
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE_COMMENT_LIST" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }">
                        <view class="solid-top" v-for="(item, index) in items" :key="index">
                            <u-card :title="item.createdBy.nickName" :sub-title="$moment(item.created_at*1000).fromNow()" :thumb="item.createdBy.avatar"
                                    padding="10" margin="15rpx" :border="false" :head-border-bottom="false" :foot-border-top="false" title-size="15rpx">
                                <view class="" slot="body">
                                    <view>
                                        <u-parse :html="item.message"></u-parse>
                                    </view>
                                </view>
                                <view class="" slot="foot">
                                    <text class="text-green">123456</text>
                                    <u-icon name="close" size="34" color="" label="删除" class="pull-right text-danger"></u-icon>
                                    <div class="clearfix"></div>
                                </view>
                            </u-card>
                        </view>
                    </template>
                </WLoadMore>
            </div>
        </div>
        <view class="comment-send-bottom">
            <view class="start-comment-view" @click="startComment">
                <view class="textarea-comment">写留言...</view>
            </view>
        </view>
        <WComment ref="WODROW_COMMENT" placeholder="留言内容至少10个字符，最多500个字符" :minStrLen="10" :maxStrLen="500" @publishComment="publishComment"></WComment>
        <ScrollTopIcon @tapIcon="tapIcon"></ScrollTopIcon>
    </view>
</template>

<script>
    import {Toast} from 'vant'
    import WLoadMore from '@/plugins/wodrow/list/LoadMore';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    import WCList from '@/plugins/wodrow/message/List';
    import WComment from "@/plugins/wodrow/message/Comment";
    export default {
        name: "MessageLeaveMessage",
        components: {
            WLoadMore,
            ScrollTopIcon,
            WCList,
            WComment
        },
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0,
                list: [],
                updateCommentId: null
            }
        },
        mounted() {
            // this.startComment()
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
                _this.updateCommentId = null;
            },
            updateComment(id) {
                let _this = this;
                _this.startComment();
                _this.updateCommentId = id;
            },
            publishComment(content) {
                let _this = this;
                let formParams = {
                    message: content,
                };
                if (_this.updateCommentId) formParams.updateCommentId = _this.updateCommentId;
                _this.$auth.post("/message/leave/publish", formParams, true, function (res) {
                    _this.$refs.WODROW_COMMENT.toggleMask();
                    _this.$refs.WODROW_COMMENT.content = "";
                    _this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData();
                }, function (msg) {
                    Toast(msg);
                });
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reLoadData()
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE_COMMENT_LIST.reachBottom()
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