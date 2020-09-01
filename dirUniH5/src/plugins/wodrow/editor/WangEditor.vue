<template>
    <view class="wodrow-wang-editor">
        <div :id="id" :placeholder="placeholder"></div>
    </view>
</template>

<script>
    let E = require('wangeditor');
    export default {
        name: "WWangEditor",
        props: {
            // ID
            id: {
                type: String,
                default: "wodrow-wang-editor"
            },
            // 占位符
            placeholder: {
                type: String,
                default: '开始输入...'
            },
            // 初始化html
            content: {
                type: String,
                default: ""
            }
        },
        model: {
            prop: "content",
            event: "editorChange"
        },
        data() {
            return {
                editor: null
            }
        },
        mounted() {
            let _this = this;
            let editor = new E('#'+_this.id);
            // editor.customConfig.uploadImgShowBase64 = true; // 使用 base64 保存图片;
            editor.customConfig.uploadImgServer = _this.$conf.apiUrl + '/site/wang-editor-upload';
            editor.customConfig.uploadFileName = 'ufile[]';
            editor.customConfig.uploadImgParams = _this.$auth.generateFormParams({wangEV: 3});
            editor.customConfig.onchange = function (html) {
                _this.$emit('editorChange', html);
            };
            editor.create();
            _this.editor = editor;
        },
        methods: {
            initContent(content) {
                this.editor.txt.html(content)
            }
        }
    }
</script>

<style scoped>

</style>