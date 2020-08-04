<template>
    <div class="user-center-index">
        <view class="content">
            123
        </view>

        <van-cell-group>
            <van-cell title="退出登录" class="cell-logout" @click="toLogout"/>
        </van-cell-group>
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
        },
        computed: {
            ...mapState(['hasLogin'])
        },
        mounted: function () {
            
        }
    }
</script>

<style>

</style>