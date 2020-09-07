<template>
    <div class="user-center-index">
        <view class="userinfo">
            <div class="container-fluid" style="height: 7rem;">
                <div class="row" style="padding-top: 1rem;padding-bottom: 1rem;">
                    <div class="col-xs-4">
                        <img class="img img-rounded img-responsive" :src="userInfo.avatar" @tap="$router.push('/pages/user/center/UpdateAvatar')"/>
                    </div>
                    <div class="col-xs-6" style="height: 5rem;">
                        <div>
                            <text style="position: absolute;bottom: 1rem;">{{userInfo.nickName}}</text>
                            <text style="position: absolute;bottom: 0;">{{userInfo.email}}</text>
                        </div>
                    </div>
                </div>
            </div>
        </view>
        <u-cell-group>
            <u-cell-item icon="setting-fill" title="修改密码" arrow-direction="right" @tap="$router.push('/pages/user/center/UpdatePassword')"></u-cell-item>
            <u-cell-item icon="chat" title="修改联系方式" arrow-direction="right" @tap="$router.push('/pages/user/center/UpdateContact')"></u-cell-item>
            <u-cell-item icon="red-packet-fill" title="修改打赏码" arrow-direction="right" @tap="$router.push('/pages/user/center/UpdateExceptionalCode')"></u-cell-item>
            <u-cell-item icon="moments" title="我的圈子" arrow-direction="right" @tap="$router.push('/pages/user/center/MyTag')"></u-cell-item>
            <u-cell-item icon="eye-fill" title="我的关注" arrow-direction="right" @tap="$router.push('/pages/user/center/MyAttention')"></u-cell-item>
            <u-cell-item icon="eye" title="我的粉丝" arrow-direction="right" @tap="$router.push('/pages/user/center/MyFans')"></u-cell-item>
            <u-cell-item icon="file-text-fill" title="我的文章" arrow-direction="right" @tap="$router.push('/pages/user/center/MyArticle')"></u-cell-item>
            <u-cell-item icon="star-fill" title="我的收藏" arrow-direction="right" @tap="$router.push('/pages/user/center/MyCollection')"></u-cell-item>
            <u-cell-item icon="chat-fill" title="留言区" arrow-direction="right" @tap="$router.push('/pages/message/LeaveMessage')"></u-cell-item>
        </u-cell-group>
        <uni-collapse accordion="true">
            <uni-collapse-item title="注册信息" v-if="userInfo.isAdmin">
                <view style="padding: 30rpx;">
                    <view v-html="userInfo.signup_message"></view>
                </view>
            </uni-collapse-item>
        </uni-collapse>
        <u-gap></u-gap>
        <view class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-warning btn-block"  @tap="toLogout" v-preventReClick>退出登录</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast, Dialog} from 'vant'
    import {mapState, mapMutations} from 'vuex'
    export default {
        name: "UserCenterIndex",
        data(){
            return {}
        },
        computed: {
            ...mapState(['hasLogin', 'userInfo'])
        },
        onPullDownRefresh() {
            let _this = this;
            _this.$auth.updateUserInfo();
            setTimeout(function () {
                uni.stopPullDownRefresh();
            }, 1000);
        },
        mounted: function () {},
        methods: {
            ...mapMutations(['logout']),
            toLogout: function () {
                let _this = this;
                Dialog.confirm({
                    title: '确认退出',
                    message: '你确认要退出登录吗？'
                }).then(() => {
                    _this.logout();
                    if (_this.hasLogin){
                        Toast("退出失败，请联系管理员")
                    }else{
                        _this.$tool.delAllCache();
                        _this.$router.push('/');
                    }
                }).catch(() => {});
            }
        }
    }
</script>

<style scoped>
    .userinfo{
        background-image: url('~@/static/userinfo-background.jpg');
    }
    .userinfo text{
        color: #fff
    }
</style>