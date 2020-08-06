<template>
    <div>
        <quill-editor
                class="editor"
                v-model="content"
                ref="myQuillEditor"
                :options="editorOption"
                @blur="onEditorBlur($event)"
                @focus="onEditorFocus($event)"
                @ready="onEditorReady($event)"
                @change="onEditorChange($event)"
        ></quill-editor>
        <van-dialog v-model="uploadImageDialogShow" title="上传图片" :show-cancel-button="true" :close-on-click-overlay="true" :before-close="uploadImages">
            <div class="container-fluid" style="padding-top: 20px; padding-bottom: 20px;">
                <div class="row">
                    <div class="col-xs-12">
                        <van-uploader v-model="imageList" multiple accept="image/*" :before-read="readIsImage" :max-count="12" :max-size="10*1024*1024" @oversize="imageOverSize"></van-uploader>
                    </div>
                </div>
            </div>
        </van-dialog>
        <van-dialog v-model="uploadAttachmentDialogShow" title="上传附件" :show-cancel-button="true" :close-on-click-overlay="true" :before-close="uploadAttachments">
            <div class="container-fluid" style="padding-top: 20px; padding-bottom: 20px;">
                <div class="row">
                    <div class="col-xs-12">
                        <van-uploader v-model="attachmentList" multiple accept="*/*" :max-count="12" :max-size="100*1024*1024" @oversize="attachmentOverSize"></van-uploader>
                    </div>
                </div>
            </div>
        </van-dialog>
    </div>
</template>
<script>
    // 工具栏配置
    const toolbarOptions = [
        ["bold", "italic", "underline", "strike"], // 加粗 斜体 下划线 删除线
        ["blockquote", "code-block"], // 引用  代码块
        [{ header: 1 }, { header: 2 }], // 1、2 级标题
        [{ list: "ordered" }, { list: "bullet" }], // 有序、无序列表
        [{ script: "sub" }, { script: "super" }], // 上标/下标
        [{ indent: "-1" }, { indent: "+1" }], // 缩进
        [{'direction': 'rtl'}],                         // 文本方向
        [{ size: ["small", false, "large", "huge"] }], // 字体大小
        [{ header: [1, 2, 3, 4, 5, 6, false] }], // 标题
        [{ color: [] }, { background: [] }], // 字体颜色、字体背景颜色
        [{ font: [] }], // 字体种类
        [{ align: [] }], // 对齐方式
        ["clean"], // 清除文本格式
        // ["link", "image", "video"], // 链接、图片、视频
        ["image", "upload"],
    ];

    import quillEditor from "../../plugin/vue-quill-editor/quill-editor";
    import {Toast} from 'vant'

    export default {
        props: {
            /*编辑器的内容*/
            value: {
                type: String
            }
        },
        components: {
            quillEditor
        },
        mounted: function() {
            this.$nextTick(function () {
                this.content = this.value;
            });
        },
        data() {
            return {
                content: this.value,
                quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
                editorOption: {
                    theme: "snow", // 'snow' or 'bubble'
                    placeholder: "请输入内容",
                    modules: {
                        toolbar: {
                            container: toolbarOptions,
                            handlers: {
                                image: this.showUploadImageDialog,
                                upload: this.showUploadAttachmentDialog
                            }
                        }
                    }
                },
                uploadImageDialogShow: false,
                imageList: [],
                uploadAttachmentDialogShow: false,
                attachmentList: []
            };
        },
        methods: {
            onEditorBlur() {
                //失去焦点事件
            },
            onEditorFocus() {
                //获得焦点事件
            },
            onEditorReady() {},
            onEditorChange() {
                //内容改变事件
                this.$emit('input', this.content)
            },
            showUploadImageDialog: function(){
                this.uploadImageDialogShow = true;
            },
            readIsImage: function (files){
                let _this = this;
                if (files instanceof Array !== true){
                    files = [files];
                }
                let result = true;
                _this.$tool.foreach(files, function (k, v) {
                    if((v.type).indexOf("image/")===-1){
                        Toast("请上传图片");
                        result = false;
                    }
                });
                return result;
            },
            uploadImages: function(action, done) {
                let _this = this;
                if (action === 'cancel') {//取消按钮
                    done();
                }else{
                    let quill = this.$refs.myQuillEditor.quill;
                    _this.$tool.foreach(_this.imageList, function (k, v) {
                        let data = _this.$auth.authPost("/user/file/upload", {base64: v.content});
                        if (data.status !== 200){
                            Toast(data.msg);
                            return ;
                        }
                        let resp_url = data.urls[0];
                        let length = quill.getSelection().index;
                        quill.insertEmbed(length, 'image', resp_url);
                        quill.setSelection(length + 1);
                        _this.imageList.splice(0, 1);
                        if (_this.imageList.length === 0){
                            done();
                        }
                    });
                }
            },
            imageOverSize: function () {
                Toast("图片大小不能超过10M");
            },
            showUploadAttachmentDialog: function () {
                this.uploadAttachmentDialogShow = true;
            },
            uploadAttachments:function(action, done){
                let _this = this;
                if (action === 'cancel') {//取消按钮
                    done();
                }else{
                    let quill = this.$refs.myQuillEditor.quill;
                    _this.$tool.foreach(_this.attachmentList, function (k, v) {
                        let data = _this.$auth.authPost("/user/file/upload", {base64: v.content});
                        if (data.status !== 200){
                            Toast(data.msg);
                            return ;
                        }
                        let resp_url = data.urls[0];
                        let length = quill.getSelection().index;
                        // quill.insertEmbed(length, 'image', resp_url);
                        quill.insertEmbed(length, 'link', {href:resp_url, innerText: v.file.name}, "api");
                        quill.setSelection(length + v.file.name.length);
                        _this.attachmentList.splice(0, 1);
                        if (_this.attachmentList.length === 0){
                            done();
                        }
                    });
                }
            },
            attachmentOverSize: function () {
                Toast("附件大小不能超过100M");
            }
        }
    };
</script>