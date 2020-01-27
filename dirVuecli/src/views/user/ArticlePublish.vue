<template>
    <div class="user-article-publish">
        <van-panel title="请发表文章" desc="暂时不能使用图片及附件" status="最好原创" id="panel-publish-rumen-article">
            <van-cell-group>
                <van-field v-model="title" label="标题" v-verify="title" :error-message="titleErrMsg" required placeholder="请输入标题"/>
                <van-field v-model="getPassword" label="浏览密码" placeholder="若不输入代表无密码访问"/>
            </van-cell-group>
            <Editor v-model="content"></Editor>
            <div class="container-fluid" v-if="articleTags.length > 0">
                <div class="row">
                    <div class="col-xs-12">
                        发布到圈子:
                    </div>
                    <div class="col-xs-12">
                        <van-tag v-for="tag in articleTags" :key="tag.id" @click="isSel(tag.id)" :type="tag.isChecked?'primary':'default'">{{tag.name}}</van-tag>
                    </div>
                </div>
            </div>
            <div slot="footer">
                <van-button size="large" type="info" @click="apply" :disabled="isBtnPublishDisable">发布</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "ArticlePublish",
        data: function () {
            return {
                user: this.$store.getters.user,
                title: "",
                titleErrMsg : "",
                content: "",
                contentErrMsg: "",
                getPassword: "",
                articleTags: [],
                oldArticleTags: [],
                tagModify: {
                    plus: [],
                    reduce: []
                },
                isBtnPublishDisable: false
            }
        },
        mounted: function(){
            let _this = this;
            _this.getArticleTags();
		},
        verify: {
            title: ["required", {
                minLength: 1
            }]
        },
        methods: {
            getArticleTags: function() {
                let _this = this;if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("circle_list"); // 测试
                let cacheList = _this.$tool.getCache("circle_list");
                if (!cacheList) {
                    let data = _this.$auth.authPost("/tag/circle/list");
                    if (data.tagTotal > 0){
                        cacheList = data.tags;
                    }else{
                        cacheList = [];
                    }
                    _this.$tool.setCache("circle_list", cacheList, 300);
                }
                _this.articleTags = cacheList.filter(function (v) {
                    if (v.isYouJoin){
                        return true;
                    }
                });
                _this.$tool.foreach(_this.articleTags, function (k) {
                    _this.articleTags[k].isChecked = false;
                });
                _this.oldArticleTags = _this.$tool.clone(_this.articleTags);
            },
            isSel: function(tag_id) {
                let _this = this;
                _this.$tool.foreach(_this.articleTags, function (k, v) {
                    if (v.id === tag_id){
                        v.isChecked = !v.isChecked;
                        _this.articleTags.splice(k, 1, v);
                    }
                });
                _this.calculate();
            },
            calculate: function() {
                let _this = this;
                if (_this.articleTags.length !== _this.oldArticleTags.length){
                    Toast("标签长度异常");
                    return ;
                }
                let len = _this.oldArticleTags.length;
                for (let i = 0; i < len; i++){
                    let oldChecked = _this.oldArticleTags[i].isChecked;
                    let newChecked = _this.articleTags[i].isChecked;
                    let tag_id = _this.oldArticleTags[i].id;
                    _this.tagModify.plus = _this.tagModify.plus.filter(function (v) {
                        return v !== tag_id;
                    });
                    _this.tagModify.reduce = _this.tagModify.reduce.filter(function (v) {
                        return v !== tag_id;
                    });
                    if (oldChecked && !newChecked) {
                        _this.tagModify.reduce.push(tag_id);
                    }
                    if (!oldChecked && newChecked) {
                        _this.tagModify.plus.push(tag_id);
                    }
                }
            },
            apply: function () {
                let _this = this;
                _this.titleErrMsg = "";
                _this.contentErrMsg = "";
                if (!_this.$verify.check()) {
                    if (_this.$verify.$errors.title) {
                        _this.titleErrMsg = _this.$verify.$errors.title[0];
                    }
                    return ;
                }
                if (_this.content.length < 100){
                    Toast("内容长度太小");
                    return ;
                }
                _this.isBtnPublishDisable = true;
                let data = _this.$auth.authPost("/article/default/new-or-update", {
                    title: _this.title,
                    content: _this.content,
                    getPassword: _this.getPassword,
                    tagModify: JSON.stringify(_this.tagModify)
                });
                if (data.is_ok === 1){
                    Toast("文章发布成功");
                    _this.$tool.delCache("article_list");
                    _this.$tool.delCache("circle_list");
                    _this.$router.push("/article/info/" + data.article.id + "/0/articleList");
                }else{
                    Toast(data.msg);
                    _this.isBtnPublishDisable = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>