<template>
    <div class="user-center-update-avatar">
        <view class="container">
            <div class="row">
                <div class="col-xs-12">
                    <button @tap="chooseImage()">选择图片</button>
                    <image class="image" :src="path"></image>
                    <kps-image-cutter @ok="onok" @cancel="oncancle" :url="url" :fixed="true" :blob="false" :maxWidth="200" :maxHeight="200"></kps-image-cutter>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import kpsImageCutter from "@/common/ksp-image-cutter/ksp-image-cutter.vue";
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
                this.path = ev.path;
                this.url = "";
            },
            oncancle() {
                // url设置为空，隐藏控件
                this.url = "";
            }
        }
    }
</script>

<style scoped>
    .image {
        width: 200px;
        height: 200px;
    }
</style>