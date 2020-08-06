<template>
    <view class="container">
        <jinEdit placeholder="请输入内容" @editOk="editOk" uploadFileUrl="/#"></jinEdit>
    </view>
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