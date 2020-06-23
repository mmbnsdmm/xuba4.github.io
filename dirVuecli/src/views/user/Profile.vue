<template>
    <div class="user-profile">
        <van-cell-group>
            <van-cell title="用户名" :value="user.username" label="" />
            <van-cell title="邮箱" :value="user.email" label="" />
        </van-cell-group>
    </div>
</template>

<script>
    import {Toast} from 'vant'

    export default {
        name: "Profile",
        data: function () {
            return {
                user: {
                    id: this.$route.params.id,
                    username: "",
                    email: "",
                    levle: 0,
                    integral: 0,
                    uclass: 1
                }
            }
        },
        mounted: function () {
            let _this = this;
            let data = _this.$auth.authPost("/user/profile/info", {user_id: _this.user.id});
            _this.$tool.log(data);
            if (!data.user){
                Toast("用户未找到或已删除");
                return ;
            }
            _this.user = data.user;
        }
    }
</script>

<style scoped>

</style>