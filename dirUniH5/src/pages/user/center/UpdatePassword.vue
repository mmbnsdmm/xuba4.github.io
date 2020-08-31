<template>
    <div class="user-center-update-password">
        <view class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h4>修改登录密码</h4>
                    <div class="form-group">
                        <label>老密码</label>
                        <input type="password" class="form-control" v-model="oldPassword" placeholder="请输入老密码">
                    </div>
                    <div class="form-group">
                        <label>新密码</label>
                        <input type="password" class="form-control" v-model="password" placeholder="请输入新密码">
                    </div>
                    <div class="form-group">
                        <label>确认密码</label>
                        <input type="password" class="form-control" v-model="passwordConfirmation" placeholder="再次确认新密码">
                    </div>
                    <button class="btn btn-primary btn-block btn-lg" :disabled="isBtnDisabled" @tap="toResetPassword">修改</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant';
    export default {
        name: "UserCenterUpdatePassword",
        data(){
            return {
                oldPassword: "",
                password : "",
                passwordConfirmation : "",
                isBtnSendVerifyCodedisabled: false,
                isBtnDisabled: false
            }
        },
        mounted: function() {},
        methods: {
            toResetPassword: function () {
                let _this = this;
                if (!_this.oldPassword){
                    Toast("老密码不能为空");
                    return;
                }
                if (!_this.password){
                    Toast("新密码不能为空");
                    return;
                }
                if (_this.password.length < 6){
                    Toast("新密码至少6位");
                    return ;
                }
                if (!_this.passwordConfirmation){
                    Toast("确认密码不能为空");
                    return;
                }
                if (_this.passwordConfirmation !== _this.password){
                    Toast("确认密码必须和新密码一致");
                    return;
                }
                _this.isBtnDisabled = true;
                _this.$auth.post('/user/center/update-password', {
                    oldPassword: _this.oldPassword,
                    newPassword: _this.password
                }, true, function (res) {
                    Toast(res.msg);
                    _this.isBtnDisabled = false;
                    _this.$router.push("/pages/user/center/Index")
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