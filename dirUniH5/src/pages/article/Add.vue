<template>
    <div class="article-add">
        <u-field v-model="title" label="标题" placeholder="请填写标题"></u-field>
        <u-field v-model="getPassword" label="获取密码" placeholder="如果设置则用户必须验证密码后才能访问"></u-field>
        <WodrowEditor id="article-edit" placeholder="请输入内容" :uploadFileUrl="$conf.apiUrl + '/user/file/upload'" v-model="content"></WodrowEditor>
        <u-gap></u-gap>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-block" @tap="toPublish()">发布</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import WodrowEditor from "@/plugins/wodrow-editor/WodrowEditor";
    export default {
        components: {
            WodrowEditor
        },
        name: "ArticleAdd",
        data: function () {
            return {
                title: "",
                getPassword: "",
                content: ""
            }
        },
        mounted() {},
        methods:{
            toPublish: function () {
                console.log(this.content);
            }
        }
    }
</script>

<style scoped>

</style>