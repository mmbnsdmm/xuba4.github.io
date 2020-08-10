<template>
	<div>
		<editor :init="init" v-model="text"></editor>
	</div>
</template>

<script>
import tinymce from 'tinymce/tinymce'
import Editor from '@tinymce/tinymce-vue'

import 'tinymce/themes/silver'
import 'tinymce/plugins/image' // 插入上传图片插件
import 'tinymce/plugins/media' // 插入视频插件
import 'tinymce/plugins/table' // 插入表格插件
import 'tinymce/plugins/lists' // 列表插件
import 'tinymce/plugins/wordcount' // 字数统计插件
import 'tinymce/icons/default/icons'

export default {
  components: {
    editor: Editor
  },
  props:{
	  content: {
		  type: String,
		  default: ''
	  }
  },
  data(){
	  return {
		  text: this.content,
		  init: {
			  language_url: '/tinymce/langs/zh_CN.js',
			  language: 'zh_CN',
			  skin_url: '/tinymce/skins/ui/oxide',
			  height: 500,
			  branding: false, //去水印
			  menubar: true, //隐藏最上方menu
			  browser_spellcheck: true, //拼写检查
			  statusbar: false, //隐藏编辑器底部的状态栏
			  paste_data_images: true, //允许粘贴图像
			  plugins: 'lists image media table wordcount',
			  toolbar: 'undo redo |  formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | lists image media table | removeformat',
			  images_upload_handler: (blobInfo,succFun,failFun) => {
				  let file = blobInfo.blob();//转化为易于理解的file对象
				  console.log('自定义上传获取file',file)
				  let param = new FormData()
				  param.append('img',file)
				  let config = {
				  		headers: {'Content-Type': 'multipart/form-data'}
				  }
				  this.axios.post('/api/upfile',param,config).then(res=>{
				  		console.log('上传成功',res)
						succFun(res.data.data.info.url)
				  }).catch(res=>{
				  		console.log('上传失败',res)
						failFun('上传失败')
				  })
			  }
		  }
	  }
  },
  mounted(){
	  tinymce.init({})
  },
  watch: {
	  content(newValue) {
		  this.text = newValue
	  },
	  text(newText){
		  this.$emit('editorChange',newText)
	  }
  }
}
</script>

<style>
</style>
