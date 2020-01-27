<template>
    <div class="center">
        <van-grid>
            <van-grid-item>
                <span style="color: #666">等级</span> <span style="color: #ccc">{{user.level}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">积分</span> <span style="color: #ccc">{{user.integral}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">可用余额</span> <span style="color: #ccc">{{user.availableAmount}}</span>
            </van-grid-item>
            <van-grid-item>
                <span style="color: #666">冻结金额</span> <span style="color: #ccc">{{user.frozen}}</span>
            </van-grid-item>
        </van-grid>
        <van-cell-group class="user-group">
            <van-cell :title="user.username" :value="this.$conf.data.uclasses[user.uclass]" />
            <van-cell v-if="canRumenApply" icon="award-o" title="申请入门" :label="applyReplyedMsg" is-link to="/user/ru-men-apply">
                <van-tag :type="applyTagType" v-if="canRumenApply">{{applyTagMsg}}</van-tag>
            </van-cell>
        </van-cell-group>

        <van-cell-group>
            <van-cell v-if="!canRumenApply" icon="records" title="发布文章" is-link to="/user/article-publish"/>
            <van-cell icon="circle" title="我的圈子" is-link to="/tag/my-circle"/>
            <van-cell v-if="false" icon="replay" title="更新用户信息" @click="updateUser"/>
            <van-cell icon="replay" title="清理缓存" @click="removeCache"/>
        </van-cell-group>

        <van-panel title="支付宝打赏二维码" desc="" status="">
            <div slot="default">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <van-image
                                    width="200"
                                    height="200"
                                    :src="user.alipay_income_image"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div slot="footer">
                <van-button size="small">按钮</van-button>
                <van-button size="small" type="danger">按钮</van-button>
            </div>
        </van-panel>

        <van-panel title="微信打赏二维码" desc="" status="">
            <div slot="default">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <van-image
                                    width="200"
                                    height="200"
                                    :src="user.weixin_income_image"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div slot="footer">
                <van-button size="small">按钮</van-button>
                <van-button size="small" type="danger">按钮</van-button>
            </div>
        </van-panel>

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
                canRumenApply: false,
                applyTagType: "primary",
                applyTagMsg: "申请中",
                applyReplyedMsg: ""
            }
        },
        mounted: function(){
            let _this = this;
            _this.$auth.updateUser();
            _this.setIncomeImage();
            let data = this.$auth.authPost("/user/center/get-rumen-article");
            if (data.is_ok !== 1) {
                _this.applyTagType = "danger";
                _this.applyTagMsg = "已拒绝";
                _this.applyReplyedMsg = "拒绝原因:没有入门文章或入门文章已被删除";
                _this.canRumenApply = true;
            }else{
                if (data.lastLogRumenApply.status === -1){
                    _this.applyTagType = "danger";
                    _this.applyTagMsg = "已拒绝";
                    _this.applyReplyedMsg = "拒绝原因:" + data.lastLogRumenApply.replyed_msg;
                }
                if (data.lastLogRumenApply.status !== 1){
                    _this.canRumenApply = true;
                }
            }
        },
        methods: {
            updateUser: function() {},
            setIncomeImage: function () {
                let _this = this;
                if (!_this.user.alipay_income_image){
                    _this.$set(_this.user, "alipay_income_image", "https://via.placeholder.com/200");
                }
                if (!_this.user.weixin_income_image){
                    _this.$set(_this.user, "weixin_income_image", "https://via.placeholder.com/200");
                }
            },
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