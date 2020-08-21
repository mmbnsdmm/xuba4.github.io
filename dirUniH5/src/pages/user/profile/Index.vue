<template>
    <div class="user-profile-index">
        <view class="userinfo-avatar">
            <div class="container-fluid" style="height: 7rem;">
                <div class="row" style="padding-top: 1rem;padding-bottom: 1rem;">
                    <div class="col-xs-4">
                        <img class="img img-rounded img-responsive" :src="profileInfo.avatar"/>
                    </div>
                    <div class="col-xs-6" style="height: 5rem;">
                        <div>
                            <u-button v-if="userInfo.id !== profileInfo.id && !profileInfo.isYourAttention" @tap="attention" type="primary" size="mini" style="position: absolute;bottom: 2rem;"><u-icon name="eye"></u-icon>关注</u-button>
                            <u-button v-if="userInfo.id !== profileInfo.id && profileInfo.isYourAttention" @tap="unAttention" type="default" size="mini" style="position: absolute;bottom: 2rem;"><u-icon name="eye-off"></u-icon>取消关注</u-button>
                            <text style="position: absolute;bottom: 1rem;">{{profileInfo.nickName}}</text>
                            <text style="position: absolute;bottom: 0;">{{profileInfo.email}}</text>
                        </div>
                    </div>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant';
    import {mapState} from 'vuex'
    export default {
        name: "UserProfileIndex",
        computed: {
            ...mapState(['userInfo'])
        },
        data() {
            return {
                profileId: "",
                profileInfo: {}
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.id){
                Toast("参数id传递异常");
                uni.navigateBack();
            }
            _this.profileId = options.id;
            _this.updateProfileInfo();
        },
        onPullDownRefresh() {
            let _this = this;
            _this.updateProfileInfo();
            setTimeout(function () {
                uni.stopPullDownRefresh();
            }, 1000);
        },
        methods: {
            updateProfileInfo() {
                let _this = this;
                let formParams = {
                    id: _this.profileId
                };
                _this.$auth.post("/user/profile/info", formParams, false, function (res) {
                    _this.$set(_this.$data, "profileInfo", res.user)
                }, function (msg) {
                    Toast(msg);
                });
            },
            attention() {
                let _this = this;
                let formParams = {
                    id: _this.profileInfo.id
                };
                _this.$auth.post("/user/profile/attention", formParams, false, function (res) {
                    _this.$set(_this.$data, "profileInfo", res.user)
                }, function (msg) {
                    Toast(msg);
                });
            },
            unAttention() {
                let _this = this;
                let formParams = {
                    id: _this.profileInfo.id
                };
                _this.$auth.post("/user/profile/un-attention", formParams, false, function (res) {
                    _this.$set(_this.$data, "profileInfo", res.user)
                }, function (msg) {
                    Toast(msg);
                });
            }
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