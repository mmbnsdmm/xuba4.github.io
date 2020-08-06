<template>
    <div class="article-add">
        <u-field v-model="title" label="标题" placeholder="请填写标题"></u-field>
        <u-field v-model="getPassword" label="获取密码" placeholder="如果设置则用户必须验证密码后才能访问"></u-field>
        <jinEdit placeholder="请输入内容" @editOk="editOk" uploadFileUrl="/#"></jinEdit>
        <view class="container"></view>
    </div>
</template>

<script>
    import jinEdit from '@/plugins/jin-edit/jin-edit.vue';
    export default {
        components: {
            jinEdit
        },
        name: "ArticleAdd",
        data: function () {
            return {
                title: "",
                getPassword: "",
                content: ""
            }
        },
        methods:{
            // 点击发布
            editOk(res) {
                console.log(res);
            }
        }
    }
</script>

<style scoped>

</style>