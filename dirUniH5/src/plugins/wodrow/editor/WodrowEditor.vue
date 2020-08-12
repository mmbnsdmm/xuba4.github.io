<template>
	<view class="wodrow-editor">
		<view class="we-toolbar">
			<WodrowIconFont class="single" type="&#xe608;" font-size="44rpx" title="撤销" @click.native="undo"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe607;" font-size="44rpx" title="重做" @click.native="redo"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe63a;" font-size="44rpx" title="分隔符"></WodrowIconFont>

			<WodrowIconFont class="single" type="&#xe75b;" font-size="44rpx" title="H1" @click.native="setHeader($event)" :color="activeColor(showH1)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe861;" font-size="44rpx" title="H2" @click.native="setHeader($event)" :color="activeColor(showH2)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe75c;" font-size="44rpx" title="H3" @click.native="setHeader($event)" :color="activeColor(showH3)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe75d;" font-size="44rpx" title="H4" @click.native="setHeader($event)" :color="activeColor(showH4)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe864;" font-size="44rpx" title="H5" @click.native="setHeader($event)" :color="activeColor(showH5)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe865;" font-size="44rpx" title="H6" @click.native="setHeader($event)" :color="activeColor(showH6)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe63a;" font-size="44rpx" title="分隔符"></WodrowIconFont>

			<WodrowIconFont class="single" type="&#xe6d9;" font-size="44rpx" title="加粗" @click.native="setBold" :color="activeColor(showBold)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe6f8;" font-size="44rpx" title="斜体" @click.native="setItalic" :color="activeColor(showItalic)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe63a;" font-size="44rpx" title="分隔符"></WodrowIconFont>

			<WodrowIconFont class="single" type="&#xec80;" font-size="44rpx" title="居中" @click.native="setCenter" :color="activeColor(showCenter)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe666;" font-size="44rpx" title="居右" @click.native="setRight" :color="activeColor(showRight)"></WodrowIconFont>
			<WodrowIconFont class="single" type="&#xe63a;" font-size="44rpx" title="分隔符"></WodrowIconFont>

			<WodrowIconFont class="single" type="&#xe6f5;" font-size="44rpx" title="插入图片" @click.native="insertImage"></WodrowIconFont>
		</view>
		<editor class="we-textarea"
			:id="id"
			:v-model="content"
			:read-only="isReadOnly"
			:placeholder="placeholder"
			:show-img-size="true"
			:show-img-toolbar="true"
			:show-img-resize="true"
			@ready="onEditorReady"
			@focus="$emit('focus')"
			@blur="$emit('blur')"
			@input="change"
			@statuschange="$emit('statuschange')"></editor>
	</view>
</template>

<script>
import WodrowIconFont from './WodrowIconfont.vue';
export default {
    name: "WodrowEditor",
    components: {
        WodrowIconFont
    },
	props: {
        // ID
        id: {
            type: String,
            default: "wodrow-editor"
        },
        // ID
        isReadOnly: {
            type: Boolean,
            default: false
        },
		// 占位符
		placeholder: {
			type: String,
			default: '开始输入...'
		},
		// 图片上传的地址
		uploadFileUrl: {
			type: String,
			default: '#'
		},
		// 上传文件时的name
		fileKeyName: {
			type: String,
			default: 'file'
		},
		// 初始化html
        content: {
			type: String,
			default: ""
		}
	},
	model: {
        prop: "content",
		event: "textareaChange"
	},
	computed:{},
	data() {
		return {
            editorCtx: null,
			showBold: false,
			showItalic: false,
			showIns: false,
			showH1: false,
			showH2: false,
			showH3: false,
			showH4: false,
			showH5: false,
			showH6: false,
			showCenter: false,
			showRight: false
		};
	},
	created() {},
	methods: {
		onEditorReady(e) {
			uni.createSelectorQuery()
				.in(this)
				.select('.we-textarea')
				.fields({
					size: true,
					context: true
				},res => {
					this.editorCtx = res.context;
					this.editorCtx.setContents({
						html: this.content
					})
				})
				.exec();
		},
        setContent(content) {
            this.editorCtx.setContents({
                html: content
            })
		},
        insertImage() {
            let _this = this;
            uni.chooseImage({
                count: 9, //默认9
                sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], //从相册选择
                success: (res) => {
                    let tempFilePaths = res.tempFilePaths;
                    tempFilePaths.forEach(function (temp) {
                        uni.uploadFile({
                            url : _this.uploadFileUrl,
                            filePath: temp,
                            name: 'ufile',
                            formData: _this.$auth.generateFormParams({}),
                            success: function (uploadFileRes) {
                                let resp = JSON.parse(uploadFileRes.data);
                                let urls = resp.data.urls;
                                urls.forEach(function (url) {
                                    _this.editorCtx.insertImage({
                                        src: url, // 此处需要将图片地址切换成服务器返回的真实图片地址
                                        alt: '图片',
										extClass: "test",
                                        success: function(e) {}
                                    });
                                })
                            }
                        });
                    });
                }
            });
        },
		activeColor(isActive) {
		    return isActive ? '#F56C6C' : '#666666';
		},
        change() {
            this.editorCtx.getContents({
                success: res => {
                    this.$emit('textareaChange', res.html);
                }
            })
        },
		undo() {
			this.editorCtx.undo();
		},
		redo() {
			this.editorCtx.redo();
		},
		setBold() {
			this.showBold = !this.showBold;
			this.editorCtx.format('bold', this.showBold);
		},
		setItalic() {
			this.showItalic = !this.showItalic;
			this.editorCtx.format('italic', this.showItalic);
		},
		setHeader(e) {
		    let _this = this;
            let h = e.currentTarget.title;
            let s = `show${h}`;
		    ['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].forEach(function (v) {
                let y = `show${v}`;
                if (h !== v){
                    _this[y] = false;
				}
                _this.editorCtx.format('header', false);
            });
            _this[s] = !_this[s];
            _this.editorCtx.format('header', _this[s] ? h : false);
		},
		setCenter() {
			this.showCenter = !this.showCenter;
			this.editorCtx.format('align', this.showCenter ? 'center' : false);
		},
		setRight() {
			this.showRight = !this.showRight;
			this.editorCtx.format('align', this.showRight ? 'right' : false);
		}
	}
};
</script>

<style scoped>
	.wodrow-editor {
		box-sizing: border-box;
		border: 1rpx solid #999;
	}
	.we-textarea {
		line-height: 160%;
		font-size: 34rpx;
		width: calc(100% - 60rpx); 
		height: auto;
		margin: 0 auto;
		max-height: 800rpx;
		word-wrap: break-word;
		word-break: normal;
		overflow:scroll;
	}
	.we-toolbar {
		border-bottom: 1rpx solid #ccc;
		padding: 0 4rpx;
		width: 100%;
		word-wrap: break-word;
		word-break: normal;
	}
</style>
