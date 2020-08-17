<template>
    <div class="user-profile-index">
        <view class="userinfo-avatar">
            <div class="container-fluid" style="height: 7rem;">
                <div class="row" style="padding-top: 1rem;padding-bottom: 1rem;">
                    <div class="col-xs-4">
                        <img class="img img-rounded img-responsive" :src="userInfo.avatar"/>
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
    </div>
</template>

<script>
    import {Toast} from 'vant';
    export default {
        name: "UserProfileIndex",
        data() {
            return {
                userInfo: {}
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.id){
                Toast("参数id传递异常");
                uni.navigateBack();
            }
            let formParams = {
                id: options.id
            };
            _this.$auth.post("/user/profile/info", formParams, false, function (res) {
                _this.$set(_this.$data, "userInfo", res.user)
            }, function (msg) {
                Toast(msg);
            });
        }
    }
</script>

<style scoped>
    .userinfo-avatar{
        background-image: url('~@/static/userinfo-background.jpg');
    }
    .userinfo-avatar text{
        color: #fff
    }
</style>