<template>
    <div class="user-center-update-contact">
        <view class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h4>修改联系方式</h4>
                    <div class="form-group">
                        <label>手机号</label>
                        <input type="text" class="form-control" v-model="mobile" placeholder="手机号">
                    </div>
                    <div class="form-group">
                        <label>微信号</label>
                        <input type="text" class="form-control" v-model="weixin" placeholder="微信号">
                    </div>
                    <div class="form-group">
                        <label>QQ</label>
                        <input type="text" class="form-control" v-model="qq" placeholder="QQ">
                    </div>
                    <button class="btn btn-primary btn-block btn-lg" :disabled="isBtnDisabled" @tap="toUpdateContact">修改</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "UserCenterUpdateContact",
        data() {
            return {
                mobile: "",
                weixin: "",
                qq: "",
                isBtnDisabled: false
            }
        },
        mounted(){
            let _this = this;
            let userInfo = _this.$store.getters.userInfo;
            _this.mobile = userInfo.mobile;
            _this.weixin = userInfo.weixin;
            _this.qq = userInfo.qq;
        },
        methods: {
            toUpdateContact() {
                let _this = this;
                let formParams = {};
                if (!_this.mobile){}else{
                    if (!/^1(3|4|5|6|7|8|9)\d{9}$/.test(_this.mobile)){
                        Toast("手机号格式不正确");
                        return ;
                    }
                    formParams.mobile = _this.mobile;
                }
                if (!_this.weixin){}else{
                    if(!/^[a-zA-Z]([-_a-zA-Z0-9]{5,19})+$/.test(_this.weixin)){
                        Toast("微信号格式不正确");
                        return ;
                    }
                    formParams.weixin = _this.weixin;
                }
                if (!_this.qq){}else{
                    if(!/^[1-9][0-9]{4,}$/.test(_this.qq)){
                        Toast("QQ号格式不正确");
                        return ;
                    }
                    formParams.qq = _this.qq;
                }
                _this.isBtnDisabled = true;
                _this.$auth.post('/user/center/update-contact', formParams, true, function (res) {
                    Toast(res.msg);
                    _this.isBtnDisabled = false;
                    _this.$auth.updateUserInfo();
                }, function (msg) {
                    Toast(msg);
                    _this.isBtnDisabled = false;
                    return ;
                })
            }
        }
    }
</script>

<style scoped>

</style>