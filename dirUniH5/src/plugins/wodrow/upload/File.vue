<template>
    <view>
        <view v-if="showfile" class="file">
            <label class="filename">{{filename}}</label>
            <label class="del" @tap="delfile">删除</label>
        </view>
        <view ref="input" class="input">

        </view>
        <button class="btn" type="primary" @tap="uploadBtn">上传文件</button>
        <uni-popup ref="popup" type="center">大小不能超过{{size}}M</uni-popup>
    </view>
</template>

<script>
    import uniPopup from "@/components/uni-popup/uni-popup.vue";
    export default {
        components: {
            uniPopup
        },
        props: ["uploadfileUrl", "limitsize","formData"],
        data() {
            return {
                showfile: false,
                filename: 'xxx',
                fileinfo: {},
                filepaths: []
            };
        },
        // 计算属性
        computed: {
            size: function() {
                return this.limitsize / 1024;
            }
        },
        // mounted 页面初始化的方法
        mounted() {
            var input = document.createElement('input')
            input.type = 'file'
            // input.accept =  '.zip'
            input.style.display = 'none'
            input.id = 'file'
            input.onchange = (event) => {
                // 上传附件 获取文件信息
                this.fileinfo = event.target.files[0];
                // console.log("文件信息", this.fileinfo);
                this.upload();
            }
            this.$refs.input.$el.appendChild(input);
        },
        methods: {
            openPopup() {
                this.$refs.popup.open()
            },
            closePopup() {
                this.$refs.popup.close()
            },
            // 上传附件按钮 绑定file的点击事件
            uploadBtn() {
                return document.getElementById("file").click();
            },
            // 上传附件
            upload() {
                let this_ = this;
                console.info("此文件信息",this_.fileinfo);
                // 校验文件大小
                let size = this_.fileinfo.size;
                if (size > this_.filesize) {
                    this_.openPopup();
                    document.getElementById("file").value = '';
                } else {
                    // 显示文件名和删除
                    this_.showfile = true;
                    this_.filename = this_.fileinfo.name;
                    // 上传api
                    if (this_.uploadfileUrl) {
                        // 转换文件路径--异步操作
                        var reader = new FileReader();
                        reader.readAsDataURL(this_.fileinfo);
                        reader.onload = function(e) {
                            // console.log(e.target.result);
                            this_.uploadAPI(e.target.result);//上传接口
                        }
                    } else {
                        console.info("无路径");
                    }
                }
            },
            // 上传接口api
            uploadAPI(path){
                // console.info(path);
                this.filepaths.push(path);
                // console.info("uploadfileUrl:",this.uploadfileUrl,"filePath:",this.filepaths,"formData:",this.formData);
                uni.uploadFile({
                    url: this.uploadfileUrl,
                    filePath:this.filepaths[0],
                    name:'file',
                    formData:this.formData,
                    success: (e) => {
                        //上传成功
                        console.log(e);
                    },
                    fail: (e) => {
                        console.log(e);
                    }
                })
            },
            // 删除附件
            delfile() {
                document.getElementById("file").value = ''; //注意清空file,否则不能上传同一个文件
                this.showfile = false;
                this.filename = '';
            },
        }
    }
</script>

<style>
    .file {
        font-size: 32upx;
    }

    .filename {
        color: #808080;
    }

    .del {
        color: #0E90CE;
        float: right;
    }

    .btn {
        width: 100%;
        margin: 20upx 0;
        color: #007AFF;
        background-color: #FFFFFF;
        border: #007AFF 2upx solid;
    }
</style>
