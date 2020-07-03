<template>
    <div class="center">
        <van-grid>
            <van-grid-item>
                <span style="color: #666">用户名</span> <span style="color: #ccc">{{user.username}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">邮箱</span> <span style="color: #ccc">{{user.email}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">余额</span> <span style="color: #ccc">{{user.amount}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">冻结金额</span> <span style="color: #ccc">{{user.frozen}}</span>
            </van-grid-item>
        </van-grid>

        <van-cell-group>
            <van-cell v-if="false" icon="replay" title="更新用户信息" @click="updateUser"/>
            <van-cell icon="replay" title="清理缓存" @click="removeCache"/>
        </van-cell-group>

        <van-cell-group>
            <van-cell title="退出登录" class="cell-logout" @click="logout"/>
        </van-cell-group>
    </div>
</template>

<script>
    // import {Toast} from 'vant'
    import {Dialog} from 'vant'
    export default {
        name: "Center",
        data(){
            return {
                user: this.$store.getters.user,
            }
        },
        mounted: function(){
            let _this = this;
            _this.$auth.updateUser();
        },
        methods: {
            updateUser: function() {},
            removeCache: function() {
                let _this = this;
                _this.$tool.delAllCache();
                _this.$router.push("/site/login");
            },
            logout: function () {
                Dialog.confirm({
                    title: '确认退出',
                    message: '你确认要退出登录吗？'
                }).then(() => {
                    this.$store.dispatch('logout').then(() => {
                        this.$router.push('/login')
                    });
                }).catch(() => {});
            }
        }
    }
</script>

<style lang="stylus">
    .cell-logout
        text-align center
        color crimson
</style>