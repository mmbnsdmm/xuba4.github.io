<template>
    <div class="article-info">
        <div v-if="!getPassword">
            <van-panel title="标题" :status="article.title">
                <div slot="header" v-if="article.isOnEdit">
                    <van-cell-group>
                        <van-field v-model="article.title" v-verify.articleEdit="articleTitle" :error-message="articleTitleErrMsg" required clearable label="文章标题" placeholder="请输入标题" />
                    </van-cell-group>
                </div>
                <div slot="default">
                    <div v-if="!article.isOnEdit" class="container-fluid" style="padding-top: 20px; padding-bottom: 20px;">
                        <div class="row">
                            <div class="col-xs-12" v-html="article.articleContent"></div>
                        </div>
                    </div>
                    <Editor v-model="article.articleContent" v-if="article.isOnEdit"></Editor>
                    <div class="container-fluid" v-if="article.isOnEdit && articleTags.length > 0">
                        <div class="row">
                            <div class="col-xs-12">
                                发布到圈子:
                            </div>
                            <div class="col-xs-12">
                                <van-tag v-for="tag in articleTags" :key="tag.id" @click="isSel(tag.id)" :type="tag.isChecked?'primary':'default'">{{tag.name}}</van-tag>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer" v-if="article.articleIsYours" style="text-align: right">
                    <van-button size="small" type="danger" @click="deleteArticle" v-if="!article.isOnEdit">删除</van-button>
                    <van-button size="small" type="primary" @click="editArticle" v-if="!article.isOnEdit">修改</van-button>
                    <van-button size="small" type="default" @click="cancelArticle" v-if="article.isOnEdit">取消</van-button>
                    <van-button size="small" type="primary" @click="saveArticle" v-if="article.isOnEdit" :disabled="isBtnSaveDisabled">保存</van-button>
                </div>
            </van-panel>
            <van-panel title="圈子" desc="" status="" v-if="!article.isOnEdit && articleCheckedTags.length > 0">
                <div slot="default">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <van-tag v-for="tag in articleCheckedTags" :key="tag.id" @click="toTag(tag.id)" type="primary">{{tag.name}}</van-tag>
                            </div>
                        </div>
                    </div>
                </div>
            </van-panel>

            <van-cell icon="user-o" title="发布者" is-link :to="'/user/profile/' + user.id"/>

            <h2 class="block-title">评论或续写</h2>
            <van-panel title="添加评论或续写">
                <div slot="header" v-if="editCCId !== 0">
                    <van-cell value="添加评论或续写">
                        <template slot="title">
                            <van-button size="small" type="primary" @click="xx(0, '1')">续写</van-button>
                            <van-button size="small" type="info" @click="xx(0, '2')">评论</van-button>
                        </template>
                    </van-cell>
                </div>
                <van-radio-group v-model="articleCCtype" v-if="editCCId === 0">
                    <van-cell-group>
                        <van-cell :title="ctypes[1]" clickable @click="articleCCtype = '1'">
                            <van-radio slot="right-icon" name="1"/>
                        </van-cell>
                        <van-cell :title="ctypes[2]" clickable @click="articleCCtype = '2'">
                            <van-radio slot="right-icon" name="2"/>
                        </van-cell>
                    </van-cell-group>
                    <Editor v-model="articleCContent" v-if="editCCId === 0"></Editor>
                </van-radio-group>
                <div slot="footer" v-if="editCCId === 0" style="text-align: right">
                    <van-button size="small" type="default" @click="cxx()">取消</van-button>
                    <van-button size="small" type="primary" @click="sxx()" :disabled="isBtnSaveDisabled">保存</van-button>
                </div>
            </van-panel>

            <h2 class="block-title">评论或续写列表</h2>
            <van-list v-model="loading" :finished="finished" finished-text="没有更多了" @load="onLoad" id="article-continuation-list">
                <van-panel v-for="item in list" :key="item.id" :title="item.label">
                    <div slot="default">
                        <div v-if="editCCId !== item.id" class="container-fluid" style="padding-top: 20px; padding-bottom: 20px;">
                            <div class="row">
                                <div class="col-xs-12" v-html="item.articleContinuationContent"></div>
                            </div>
                        </div>
                    </div>
                    <van-radio-group v-model="articleCCtype" v-if="editCCId === item.id">
                        <van-cell-group>
                            <van-cell :title="ctypes[1]" clickable @click="articleCCtype = '1'">
                                <van-radio slot="right-icon" name="1"/>
                            </van-cell>
                            <van-cell :title="ctypes[2]" clickable @click="articleCCtype = '2'">
                                <van-radio slot="right-icon" name="2"/>
                            </van-cell>
                        </van-cell-group>
                        <Editor v-model="articleCContent" v-if="editCCId === item.id"></Editor>
                    </van-radio-group>
                    <div slot="footer" style="text-align: right">
                        <van-button size="small" type="danger" @click="dxx(item.id)" v-if="item.isYours && editCCId !== item.id">删除</van-button>
                        <van-button size="small" type="primary" @click="xx(item.id, item.ctype)" v-if="item.isYours && editCCId !== item.id">编辑</van-button>
                        <van-button size="small" type="default" @click="cxx()" v-if="editCCId === item.id">取消</van-button>
                        <van-button size="small" type="primary" @click="sxx()" v-if="editCCId === item.id" :disabled="isBtnSaveDisabled">保存</van-button>
                    </div>
                </van-panel>
            </van-list>
        </div>
        <div v-if="getPassword">
            <h2 class="block-title">此文档需要进行密码验证</h2>
            <van-cell-group>
                <van-field v-model="inputPassword" required center clearable label="密码" placeholder="请输入访问密码">
                    <van-button slot="button" size="small" type="primary" @click="checkPassword">确定</van-button>
                </van-field>
            </van-cell-group>
        </div>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    import {Dialog} from 'vant'
    export default {
        name: "Info",
        data: function() {
            return {
                getPassword: null,
                inputPassword: null,
                user: this.$store.getters.user,
                ctypes: this.$conf.data.ctypes,
                articleId: this.$route.params.id,
                isUpdate: this.$route.params.isUpdate,
                article: {
                    title: "",
                    articleIsYours: false,
                    articleContent: "",
                    articleTags: [],
                    isOnEdit: false
                },
                articleTitle: "",
                articleTitleErrMsg: "",
                articleTags: [],
                oldArticleTags: [],
                articleCheckedTags: [],
                tagModify: {
                    plus: [],
                    reduce: []
                },
                articleCCtype: '2',
                articleCContent: "",
                editCCId: -1,
                list: [],
                listIndex: [],
                index: 0,
                loading: false,
                finished: true,
                isBtnSaveDisabled: false
            };
        },
        verify: {
            articleTitle: ["required", {
                minLength: 1
            }],
        },
        mounted: function () {
            let _this = this;
            _this.initArticle();
        },
        methods: {
            initArticle: function() {
                let _this = this;
                if (!_this.$store.getters.isLgRumen){
                    Toast("未入门，无权查看文章");
                    _this.$router.push("/user/center");
                    return ;
                }
                if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("article_id_" + _this.articleId); // 测试
                _this.article = _this.$tool.getCache("article_id_" + _this.articleId);
                if (!_this.article || _this.isUpdate > 0 || !_this.article.articleContent){
                    let data = _this.$auth.authPost("/article/default/info", {id: _this.articleId, isGetContent: 1, isGetTags: 1});
                    if (data.article){
                        _this.article = data.article;
                        _this.article.articleContent = data.articleContent;
                        _this.article.articleTags = data.articleTags;
                        _this.$tool.setCache("article_id_" + _this.articleId, _this.article);
                    }else{
                        Toast("文章未找到或已删除");
                        _this.deleteArticleBack();
                        return ;
                    }
                }
                _this.getPassword = _this.$tool.clone(_this.article.get_password);
                _this.inputPassword = _this.$tool.getCache("article_" + _this.articleId + "_user_" + _this.user.id + "_password", _this.inputPassword);
                if (_this.getPassword === _this.inputPassword){
                    _this.getPassword = null;
                }
                _this.article.articleIsYours = _this.article.created_by === _this.user.id;
                _this.getArticleTags();
                _this.reLoad();
            },
            checkPassword: function() {
                let _this = this;
                if (_this.getPassword !== _this.inputPassword){
                    Toast("访问密码错误");
                    return ;
                }
                _this.getPassword = null;
                _this.$tool.setCache("article_" + _this.articleId + "_user_" + _this.user.id + "_password", _this.inputPassword);
            },
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
                let checkedIds = [];
                _this.$tool.foreach(_this.article.articleTags, function (k, v) {
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
                _this.getArticleCheckedTags();
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
            getArticleCheckedTags: function() {
                let _this = this;
                _this.$tool.foreach(_this.oldArticleTags, function (k, v) {
                    if (v.isChecked){
                        _this.articleCheckedTags.push(v);
                    }
                });
            },
            toTag: function(id) {
                let _this = this;
                _this.$router.push('/tag/info-circle/' + id)
            },
            editArticle: function () {
                this.$set(this.article, 'isOnEdit', true);
            },
            deleteArticle: function () {
                let _this = this;
                Dialog.confirm({
                    title: '确认删除',
                    message: '你确认要删除这篇文章吗？'
                }).then(() => {
                    let data = _this.$auth.authPost("/article/default/delete", {
                        id: _this.articleId
                    });
                    if (data.is_ok === 1){
                        Toast("删除成功");
                        _this.deleteArticleBack();
                    }else{
                        Toast(data.msg);
                    }
                }).catch(() => {});
            },
            deleteArticleBack: function() {
                let _this = this;
                _this.$tool.delCache("article_list");
                _this.$tool.delCache("circle_list");
                _this.$tool.delCache("article_id_" + _this.articleId);
                let _fromTag = this.$route.params.from;
                switch (_fromTag) {
                    case "articleList":
                        _this.$router.push("/article/list");
                        break;
                    default:
                        _this.$router.push("/tag/info-circle/" + _fromTag);
                        break;
                }
            },
            saveArticle: function () {
                let _this = this;
                _this.articleTitle = _this.article.title;
                _this.articleTitleErrMsg = "";
                if (!_this.$verify.check('articleEdit')) {
                    if (_this.$verify.$errors.articleTitle) {
                        _this.articleTitleErrMsg = this.$verify.$errors.articleTitle[0];
                    }
                    return ;
                }
                if (_this.article.articleContent.length < 100){
                    Toast("内容长度太小");
                    return ;
                }
                _this.isBtnSaveDisabled = true;
                let data = _this.$auth.authPost("/article/default/new-or-update", {
                    id: _this.articleId,
                    title: _this.article.title,
                    tagModify: JSON.stringify(_this.tagModify),
                    content: _this.article.articleContent
                });
                if (data.is_ok === 1){
                    Toast("修改成功");
                    _this.$tool.delCache("article_id_" + _this.articleId);
                    _this.$set(_this.article, 'isOnEdit', false);
                    _this.isBtnSaveDisabled = false;
                }else{
                    Toast(data.msg);
                    _this.isBtnSaveDisabled = false;
                }
            },
            cancelArticle: function () {
                this.$set(this.article, 'isOnEdit', false);
            },
            xx: function(id = 0, ctype) {
                let _this = this;
                _this.editCCId = id;
                _this.articleCCtype = ctype;
                _this.articleCContent = "";
                if (id > 0){
                    _this.articleCContent = _this.$tool.getCache("article_continuation_id_" + id).articleContinuationContent;
                }
            },
            sxx: function() {
                let _this = this;
                let minLength = 10;
                switch (_this.articleCCtype) {
                    case '1':
                        minLength = 100;
                        break;
                    case  '2':
                        minLength = 10;
                        break;
                    default:
                        _this.$tool.log(_this.articleCCtype);
                        break;
                }
                if (_this.articleCContent.length < minLength){
                    Toast("内容长度太小");
                    return ;
                }
                _this.isBtnSaveDisabled = true;
                let data = _this.$auth.authPost("/article/continuation/new-or-update", {
                    id: _this.editCCId,
                    article_id: _this.articleId,
                    ctype: _this.articleCCtype,
                    content: _this.articleCContent
                });
                if (data.is_ok === 1){
                    Toast("保存成功");
                    _this.$tool.delCache("article_continuation_id_" + _this.editCCId);
                    if (_this.editCCId < 1){
                        _this.$tool.delCache("article_list_continuation_" + _this.articleId);
                    }
                    _this.editCCId = -1;
                    _this.articleCContent = "";
                    _this.articleCCtype = 2;
                    _this.reLoad();
                    _this.isBtnSaveDisabled = false;
                }else{
                    Toast(data.msg);
                    _this.isBtnSaveDisabled = false;
                }
            },
            cxx: function() {
                let _this = this;
                _this.editCCId = -1;
                _this.articleCContent = "";
            },
            dxx: function(id = 0) {
                let _this = this;
                if (id < 1){
                    Toast("没有要删除的对象");
                }
                Dialog.confirm({
                    title: '确认删除',
                    message: '你确认要删除吗？'
                }).then(() => {
                    let data = _this.$auth.authPost("/article/continuation/delete", {
                        id: id
                    });
                    if (data.is_ok === 1){
                        _this.dxxBack();
                    }
                    Toast(data.msg);
                }).catch(() => {});
            },
            dxxBack: function (id) {
                let _this = this;
                _this.$tool.delCache("article_list_continuation_" + _this.articleId);
                _this.$tool.delCache("article_continuation_id_" + id);
                _this.reLoad();
            },
            reLoad: function() {
                let _this = this;
                _this.finished = false;
                _this.loading = true;
                _this.list = [];
                _this.index = 0;
                _this.getListIndex();
                _this.onLoad();
            },
            overSize: function () {
                Toast("文件大小不能超过50M");
            },
            getListIndex: function() {
                let _this = this;
                if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("article_list_continuation_" + _this.articleId); // 测试
                let cacheList = _this.$tool.getCache("article_list_continuation_" + _this.articleId);
                if (!cacheList) {
                    let data = _this.$auth.authPost("/article/continuation/list", {article_id: _this.articleId});
                    if (data.articleContinuationTotal > 0){
                        cacheList = data.articleContinuations;
                    }else{
                        cacheList = [];
                    }
                    _this.$tool.setCache("article_list_continuation_" + _this.articleId, cacheList, 300);
                }
                _this.listIndex = cacheList;
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
                        if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("article_continuation_id_" + indexInfo.id); // 测试
                        let articleContinuationCache = _this.$tool.getCache("article_continuation_id_" + indexInfo.id);
                        if (!articleContinuationCache){
                            let formData = {id: indexInfo.id, isGetContent: 1};
                            let data = _this.$auth.authPost("/article/continuation/info", formData);
                            if (!data){
                                _this.index = i;
                                _this.loading = false;
                                _this.finished = true;
                                return ;
                            }
                            data.articleContinuation.articleContinuationContent = data.articleContinuationContent;
                            articleContinuationCache = data.articleContinuation;
                            _this.$tool.setCache("article_continuation_id_" + indexInfo.id, articleContinuationCache);
                        }
                        let articleContinuationInfo = articleContinuationCache;
                        articleContinuationInfo.isYours = articleContinuationInfo.created_by - this.user.id === 0;
                        articleContinuationInfo.isUpdate = false;
                        articleContinuationInfo.value = "";
                        articleContinuationInfo.label = _this.ctypes[articleContinuationInfo.ctype];
                        articleContinuationInfo.ctype = String(articleContinuationInfo.ctype);
                        articleContinuationInfo.to = "/article/info/" + articleContinuationInfo.id;
                        if (articleContinuationInfo.updated_at > articleContinuationCache.updated_at) {
                            articleContinuationInfo.isUpdate = true;
                            articleContinuationInfo.to += "/1";
                        }
                        _this.list.push(articleContinuationInfo);
                    }
                    this.index = i;
                    this.loading = false;
                }, 500);
            }
        }
    }
</script>

<style scoped>

</style>