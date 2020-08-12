<template>
    <div class="article-update">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li>
                <text class="text-blue" @tap="$router.push('/pages/article/List')">文章列表</text>
            </li>
            <li class="active">{{article.title}}</li>
        </ol>
        <Artilce_Form :article="article"></Artilce_Form>
    </div>
</template>

<script>
    import Artilce_Form from './_Form';
    import {Toast} from 'vant'
    export default {
        name: "ArticleUpdate",
        components: {
            Artilce_Form
        },
        data() {
            return {
                article: {}
            }
        },
        onLoad(options) {
            let _this = this;
            if (!options.id){
                Toast("参数id传递异常");
                uni.navigateBack();
            }
            let articleId = options.id;
            _this.$auth.post("/article/default/view", {id: articleId}, true, function (res) {
                let article = res.article;
                /*_this.$_.forEach(_this.article, function (v, k) {
                    _this.$set(_this.article, k, article[k]);
                });*/
                _this.$set(_this.$data, "article", article);
            }, function (msg) {
                Toast(msg);
            })
        }
    }
</script>

<style scoped>

</style>