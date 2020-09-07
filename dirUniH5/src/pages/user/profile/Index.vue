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
        <u-cell-group>
            <u-cell-item icon="eye-fill" title="圈子" arrow-direction="right" @tap="toCircles"></u-cell-item>
            <u-cell-item icon="eye-fill" title="关注" arrow-direction="right" @tap="toAttentions"></u-cell-item>
            <u-cell-item icon="eye" title="粉丝" arrow-direction="right" @tap="toFanses"></u-cell-item>
            <u-cell-item icon="file-text-fill" title="文章" arrow-direction="right" @tap="toArticles"></u-cell-item>
            <u-cell-item icon="star-fill" title="收藏" arrow-direction="right" @tap="toCollections"></u-cell-item>
        </u-cell-group>
        <uni-collapse accordion="true">
            <uni-collapse-item title="联系方式">
                <view style="padding: 30rpx;">
                    <p>手机号: <code>{{profileInfo.mobile?profileInfo.mobile:'未设置'}}</code></p>
                    <p>微信: <code>{{profileInfo.weixin?profileInfo.weixin:'未设置'}}</code></p>
                    <p>QQ: <code>{{profileInfo.qq?profileInfo.qq:'未设置'}}</code></p>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="注册信息" v-if="userInfo.isAdmin">
                <view style="padding: 30rpx;">
                    <view v-html="profileInfo.signup_message"></view>
                </view>
            </uni-collapse-item>
            <uni-collapse-item title="打赏">
                <view class="container-fluid" style="padding: 30rpx;">
                    <div class="row">
                        <div class="col-xs-4">
                            <p>支付宝：</p>
                            <u-link :href="profileInfo.alipay_exceptional_url" v-if="profileInfo.alipay_exceptional_url">点击去打赏</u-link>
                        </div>
                        <div class="col-xs-8">
                            <image style="width: 200px; height: 200px;" mode="aspectFill" :src="profileInfo.alipay_exceptional_code?profileInfo.alipay_exceptional_code:QrcodePlaceholder"></image>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <p>微信：</p>
                            <u-link :href="profileInfo.weixin_exceptional_url" v-if="profileInfo.weixin_exceptional_url">点击去打赏</u-link>
                        </div>
                        <div class="col-xs-8">
                            <image style="width: 200px; height: 200px;" mode="aspectFill"  :src="profileInfo.weixin_exceptional_code?profileInfo.weixin_exceptional_code:QrcodePlaceholder"></image>
                        </div>
                    </div>
                </view>
            </uni-collapse-item>
        </uni-collapse>
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
                profileInfo: {},
                QrcodePlaceholder: "https://via.placeholder.com/200x200?text=200x200"
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.id){
                Toast("参数id传递异常");
                uni.navigateBack();
            }
            _this.profileId = options.id;
            // _this.profileId = 7;
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
            },
            toCircles() {
                uni.navigateTo({
                    url: "/pages/user/profile/Tags?profileId=" + this.profileId
                });
            },
            toAttentions() {
                uni.navigateTo({
                    url: "/pages/user/profile/Attentions?profileId=" + this.profileId
                });
            },
            toFanses() {
                uni.navigateTo({
                    url: "/pages/user/profile/Fanses?profileId=" + this.profileId
                });
            },
            toArticles() {
                uni.navigateTo({
                    url: "/pages/user/profile/Articles?profileId=" + this.profileId
                });
            },
            toCollections() {
                uni.navigateTo({
                    url: "/pages/user/profile/Collections?profileId=" + this.profileId
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