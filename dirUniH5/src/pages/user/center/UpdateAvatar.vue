<template>
    <div class="user-center-update-avatar">
        <u-gap></u-gap>
        <view class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="preview">
                        <img :src="path" alt="" class="image" width="200" height="200">
                    </div>
                    <kps-image-cutter @ok="onok" @cancel="oncancle" :url="url" :fixed="true" :blob="false" :width="200" :height="200"></kps-image-cutter>
                    <u-gap></u-gap>
                    <button class="btn btn-info btn-block" @tap="chooseImage()">选择图片</button>
                    <button class="btn btn-warning btn-block" @tap="resetIt()">重置</button>
                    <button class="btn btn-danger btn-block" @tap="selectIt()">确定修改</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import kpsImageCutter from "@/plugins/ksp-image-cutter/ksp-image-cutter.vue";
    import {Toast} from 'vant'
    export default {
        name: "UserCenterUpdateAvatar",
        components: {kpsImageCutter},
        data() {
            return {
                url: "",
                path: ""
            }
        },
        onLoad() {
            let _this = this;
            _this.path = _this.$store.getters.userInfo.avatar;
            this.url = "";
        },
        methods: {
            chooseImage() {
                uni.chooseImage({
                    success: (res) => {
                        // 设置url的值，显示控件
                        this.url = res.tempFilePaths[0];
                    }
                });
            },
            onok(ev) {
                let _this = this;
                _this.path = ev.path;
                _this.url = "";
            },
            oncancle() {
                let _this = this;
                _this.url = "";
            },
            resetIt: function () {
                let _this = this;
                _this.url = "";
                _this.path = _this.path = _this.$store.getters.userInfo.avatar;
            },
            selectIt: function () {
                let _this = this;
                if (_this.path === _this.$store.getters.userInfo.avatar) {
                    Toast("请选取图片后后上传");
                    return ;
                }
                _this.$auth.post("/user/file/upload", {base64: _this.path}, true, function (res) {
                    let avatar = res.urls[0];
                    _this.$auth.post("/user/center/update-avatar", {newAvatarUrl: avatar}, true, function (res) {
                        Toast("修改头像成功");
                        _this.$auth.updateUserInfo();
                        _this.$router.push("/pages/user/center/Index");
                    }, function (msg) {
                        Toast(msg)
                    });
                }, function (msg) {
                    Toast(msg)
                })
            }
        }
    }
</script>

<style scoped>
    .image {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }
</style>