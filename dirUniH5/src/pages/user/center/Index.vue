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
            <u-cell-item icon="setting-fill" title="修改登录密码" arrow-direction="right" @tap="$router.push('/pages/user/center/UpdatePassword')"></u-cell-item>
        </u-cell-group>
        <u-gap></u-gap>
        <view class="container">
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
    import { mapState, mapMutations } from 'vuex'
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
                        this.$router.push('/')
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