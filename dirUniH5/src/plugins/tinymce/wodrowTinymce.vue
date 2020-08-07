<template>
    <div class="wodrow-tinymce">
        <textarea :id="id" v-model="tinymceValue"></textarea>
    </div>
</template>

<script>
    import tinymce from 'tinymce/tinymce'
    import Editor from "@/plugins/tinymce/wodrow-tinymce";
    export default {
        name: "WodrowTinymce",
        components:{
            Editor
        },
        props:{
            id: String,
            tinymceValue:{
                type:String,
                default:''
            }
        },
        data(){
            return{
                init:{
                    selector: '#'+this.id,
                    language_url: "/static/tinymce/langs/zh_CN.js",
                    language: 'zh_CN',//语言
                    skin_url: "/static/tinymce/skins/ui/oxide",
                    height: 400,//编辑器高度
                    branding: false,//是否禁用“Powered by TinyMCE”
                    menubar: true,//顶部菜单栏显示
                    elementpath: true,  //禁用编辑器底部的状态栏
                    paste_data_images: true,// 允许粘贴图像
                    plugins:'image',
                    toolbar:['formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | lists image media table | removeformat'],
                    images_upload_handler: (blobInfo, success, failure) => {
                        let formData = new FormData();
                        formData.append('folder', 'editor/img');
                        formData.append('upfile', blobInfo.blob(), blobInfo.filename());
                        this.$api.uploadFile(formData).then(response => {
                            if(response.code === 1){
                                let fileArr = response.data.files[0].url;
                                success(fileArr);
                            }
                        }).catch(()=>{
                            failure("上传失败")
                        })
                    }
                }
            }
        },
        mounted() {
            tinymce.init(this.init);
        }
    }
</script>

<style scoped>
    .tox-statusbar__branding {
        display: none;
    }
</style>