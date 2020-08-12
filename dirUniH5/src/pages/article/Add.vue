<template>
    <div class="article-add">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li>
                <text class="text-blue" @tap="$router.push('/pages/article/List')">文章列表</text>
            </li>
            <li class="active">创建文章</li>
        </ol>
        <Artilce_Form></Artilce_Form>
    </div>
</template>

<script>
    import Artilce_Form from './_Form'
    export default {
        name: "ArticleAdd",
        components: {
            Artilce_Form
        },
        data: function () {
            return {}
        }
    }
</script>

<style scoped>

</style>