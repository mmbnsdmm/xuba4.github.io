<template>
    <div class="tag-info-circle">
        <van-search v-model="searchValue" placeholder="请输入搜索关键词" shape="round" @search="search" show-action>
            <div slot="action" @click="search">搜索</div>
        </van-search>
        <van-list  v-model="loading" :finished="finished" finished-text="没有更多了" @load="onLoad">
            <van-cell v-for="item in list" :key="item.id" is-link :to="item.to">
                <template slot="title">
                    <van-icon v-if="!item.isYours" slot="icon" name="label-o" style="line-height: inherit;"/>
                    <van-icon v-if="item.isYours" slot="icon" name="manager-o" style="line-height: inherit;"/>
                    <van-icon v-if="item.isNew" slot="icon" name="award-o" style="line-height: inherit;"/>
                    <span class="">{{item.title}}</span>
                    <van-tag v-if="item.is_rumen" type="default">入门文章</van-tag>
                    <van-tag v-if="item.get_password" type="danger">有密码</van-tag>
                    <van-tag v-if="item.isNew" type="primary">新</van-tag>
                </template>
                <template slot="default">
                    <van-tag v-if="item.isUpdate" type="warning">有更新</van-tag>
                </template>
            </van-cell>
        </van-list>
    </div>
</template>

<script>
    export default {
        name: "InfoCircle",
        data: function () {
            return {
                tagId: this.$route.params.id,
                searchValue: "",
                list: [],
                listIndex: [],
                index: 0,
                loading: false,
                finished: true
            }
        },
        mounted: function() {
            let _this = this;
            _this.search();
        },
        methods: {
            search: function () {
                let _this = this;
                _this.finished = false;
                _this.loading = true;
                _this.list = [];
                _this.index = 0;
                _this.getListIndex();
                _this.onLoad();
            },
            getListIndex: function() {
                let _this = this;
                if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("circle_article_list_" + _this.tagId); // 测试
                let cacheList = _this.$tool.getCache("circle_article_list_" + _this.tagId);
                if (!cacheList) {
                    let data = _this.$auth.authPost("/article/default/list", {tag_id: _this.tagId});
                    if (data.articleTotal > 0){
                        cacheList = data.articles;
                    }else{
                        cacheList = [];
                    }
                    _this.$tool.setCache("circle_article_list_" + _this.tagId, cacheList, 300);
                }
                _this.listIndex = cacheList.filter((v) => {
                    return v.title.search(_this.searchValue) > -1;
                });
            },
            onLoad() {
                setTimeout(() => {
                    let _this = this;
                    let i = _this.index;
                    let limit = _this.index + 10;
                    for (i; i < limit; i++) {
                        if (_this.listIndex.length === _this.list.length){
                            _this.index = i;
                            _this.loading = false;
                            _this.finished = true;
                            return ;
                        }
                        let indexInfo = _this.listIndex[i];
                        if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("article_id_" + indexInfo.id); // 测试
                        let articleCache = _this.$tool.getCache("article_id_" + indexInfo.id);
                        if (!articleCache){
                            let formData = {id: indexInfo.id};
                            let data = _this.$auth.authPost("/article/default/info", formData);
                            if (!data){
                                _this.index = i;
                                _this.loading = false;
                                _this.finished = true;
                                return ;
                            }
                            articleCache = data.article;
                            _this.$tool.setCache("article_id_" + indexInfo.id, articleCache);
                        }
                        let articleInfo = articleCache;
                        articleInfo.isYours = articleInfo.created_by - this.$store.getters.user.id === 0;
                        articleInfo.isUpdate = false;
                        articleInfo.isNew = false;
                        articleInfo.label = "label";
                        articleInfo.to = "/article/info/" + articleInfo.id;
                        if (indexInfo.updated_at > articleCache.updated_at) {
                            articleInfo.isUpdate = true;
                            articleInfo.to += "/1";
                        }else{
                            articleInfo.to += "/0";
                        }
                        articleInfo.to += "/" + _this.tagId;
                        if (_this.$tool.getTimestamp() - indexInfo.updated_at > 3600 * 24 * 3) {
                            articleInfo.isNew = true;
                        }
                        _this.list.push(articleInfo);
                    }
                    this.index = i;
                    this.loading = false;
                }, 500);
            }
        }
    }
</script>

<style>

</style>