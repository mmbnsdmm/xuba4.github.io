<template>
    <div class="user-ru-men-apply">
        <van-panel title="请发表入门文章" desc="可以是图片,附件或不少于3000字的文章" status="必须原创" id="panel-publish-rumen-article">
            <van-cell-group>
                <van-field v-model="title" label="标题" v-verify.rumenApply="title" :error-message="titleErrMsg" required placeholder="请输入标题"/>
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
                <van-button size="large" type="info" @click="apply" :disabled="isBtnPublishDisable">申请入门</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "RuMenApply",
        components: {},
        mounted: function(){
            let _this = this;
            let data = _this.$auth.authPost("/user/center/get-rumen-article");
            if (data && data.is_ok === 1){
                _this.title = data.rumenArticle.title;
                _this.content = data.rumenArticle.content;
                _this.activeTags = data.rumenArticle.activeTags;
            }
            _this.getArticleTags();
        },
        data: function () {
            return {
                user: this.$store.getters.user,
                title: "",
                titleErrMsg : "",
                content: "",
                getPassword: "",
                activeTags: [],
                articleTags: [],
                oldArticleTags: [],
                articleCheckedTags: [],
                tagModify: {
                    plus: [],
                    reduce: []
                },
                isBtnPublishDisable: false
            }
        },
        verify: {
            title: ["required", {
                minLength: 1
            }],
        },
        methods: {
            getArticleTags: function() {
                let _this = this;
                if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("circle_list"); // 测试
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
                let checkedIds = [];
                _this.$tool.foreach(_this.activeTags, function (k, v) {
                    checkedIds[k] = v.id;
                });
                _this.$tool.foreach(_this.articleTags, function (k, v) {
                    if (checkedIds.indexOf(v.id) > -1){
                        _this.articleTags[k].isChecked = true;
                    }else{
                        _this.articleTags[k].isChecked = false;
                    }
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
                if (!_this.$verify.check('rumenApply')) {
                    if (_this.$verify.$errors.title) {
                        _this.titleErrMsg = this.$verify.$errors.title[0];
                    }
                    return ;
                }
                let content = this.content;
                if (content.length < 100){
                    Toast("内容长度太小");
                    return ;
                }
                _this.isBtnPublishDisable = true;
                let data = _this.$auth.authPost("/user/center/rumen-apply", {
                    title: _this.title,
                    tagModify: JSON.stringify(_this.tagModify),
                    content: content
                });
                if (data.is_ok === 1){
                    Toast("已发送申请");
                    _this.$router.push("/user/center");
                }else{
                    Toast(data.msg);
                    _this.isBtnPublishDisable = false;
                }
            },
            overSize: function () {
                Toast("文件大小不能超过50M");
            }
        }
    }
</script>

<style lang="stylus">

</style>