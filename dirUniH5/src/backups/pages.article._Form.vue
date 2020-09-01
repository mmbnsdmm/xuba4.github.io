<template>
    <div class="article-_form">
        <u-field v-model="title" label="标题" placeholder="请填写标题"></u-field>
        <u-field v-model="get_password" label="获取密码" placeholder="如果设置则用户必须验证密码后才能访问文章"></u-field>
        <WodrowEditor ref="WODROW_ARTICLE_EDITOR" id="article-edit" placeholder="请输入内容" :uploadFileUrl="$conf.apiUrl + '/user/file/upload'" v-model="content"></WodrowEditor>
        <u-gap></u-gap>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <u-radio-group v-model="status">
                        <u-radio
                                v-for="(item, index) in statusList" :key="index"
                                :name="item.v"
                                :disabled="item.disabled"
                        >
                            {{item.name}}
                        </u-radio>
                    </u-radio-group>
                </div>
                <div class="col-xs-12">
                    <u-radio-group v-model="create_type">
                        <u-radio
                                v-for="(item, index) in createTypeList" :key="index"
                                :name="item.v"
                                :disabled="item.disabled"
                        >
                            {{item.name}}
                        </u-radio>
                    </u-radio-group>
                </div>
                <div class="col-xs-12">
                    <u-gap></u-gap>
                    <view class="tags pull-left" v-for="(utag, i) in myTags" :key="i">
                        <u-tag :text="utag.tag_name" :type="utag._isSel?'success':'info'" @tap="toggleTag(i)"/>
                    </view>
                    <view class="clearfix"></view>
                </div>
            </div>
        </div>
        <u-gap></u-gap>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-block" :disabled="isBtnDisabled" @tap="toPublish()">发布</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import WodrowEditor from "@/plugins/wodrow/editor/WodrowEditor";
    import {Toast} from 'vant';
    export default {
        components: {
            WodrowEditor
        },
        name: "Article_Form",
        props: {
            article: {
                type: Object,
                default: function () {
                    return {
                        id: "",
                        title: "",
                        get_password: "",
                        content: "",
                        status: 10,
                        aTagIds: [],
                        create_type: 1
                    }
                }
            }
        },
        data: function () {
            return {
                id: "",
                title: "",
                get_password: "",
                content: "",
                status: "",
                statusList: [],
                create_type: 1,
                createTypeList: [],
                aTagIds: [],
                myTags: [],
                oldMyTags: [],
                tagModify: {
                    plus: [],
                    reduce: []
                },
                isBtnDisabled: false
            }
        },
        mounted() {
            let _this = this;
            _this.$_.forEach(_this.article, function (v, k) {
                _this[k] =v;
            });
            let statusDesc = _this.$conf.serverData.enums.article.statusDesc;
            _this.$_.forEach(statusDesc, function (v, k) {
                if (v !== -10){
                    let item = {
                        name: v,
                        v: k,
                        disabled: false
                    };
                    _this.statusList.push(item);
                }
            });
            let createTypeList = _this.$conf.serverData.enums.article.createTypeDesc;
            _this.$_.forEach(createTypeList, function (v, k) {
                if (v !== -10){
                    let item = {
                        name: v,
                        v: k,
                        disabled: false
                    };
                    _this.createTypeList.push(item);
                }
            });
            _this.$auth.post("/user/center/get-my-tags", {}, true, function (res) {
                let uTags = res.utags;
                _this.$_.forEach(uTags, function (v, k) {
                    let tag = v;
                    let findIndex = _this.aTagIds.indexOf(v.tag_id);
                    tag._isSel = findIndex !== -1;
                    _this.myTags.push(tag);
                });
                _this.oldMyTags = _this.$tool.clone(_this.myTags);
            }, function (msg) {
                Toast(msg);
            });
        },
        watch: {
            article:{
                handler(newValue, oldValue){
                    let _this = this;
                    _this.id = newValue.id;
                    _this.title = newValue.title;
                    _this.get_password = newValue.get_password;
                    _this.content = newValue.content;
                    _this.status = newValue.status;
                    _this.aTagIds = newValue.aTagIds;
                    _this.$refs.WODROW_ARTICLE_EDITOR.setContent(newValue.content);
                },
                deep:true
            }
        },
        methods:{
            toPublish: function () {
                let _this = this;
                if (!_this.title){
                    Toast("标题不能为空");
                    return;
                }
                if (!_this.content){
                    Toast("内容不能为空");
                    return;
                }
                if (_this.content.length < 50){
                    Toast("内容必须不小于50个字符");
                    return;
                }
                _this.isBtnDisabled = true;
                let formParams = {
                    title: _this.title,
                    content: _this.content,
                    status: _this.status,
                    create_type: _this.create_type,
                    tagModify: JSON.stringify(_this.tagModify)
                };
                if (_this.id)formParams['id'] = _this.id;
                if (_this.get_password)formParams['get_password'] = _this.get_password;
                _this.$auth.post('/article/default/publish', formParams, true, function (res) {
                    Toast(res.msg);
                    _this.isBtnDisabled = false;
                    _this.title = _this.content = _this.get_password = _this.id = "";
                    _this.$refs.WODROW_ARTICLE_EDITOR.setContent(_this.content);
                    _this.statas = 10;
                    let article = res.article;
                    uni.redirectTo({
                        url: "/pages/article/View?id=" + article.id + "&isLast=" + true
                    });
                }, function (msg) {
                    Toast(msg);
                    _this.isBtnDisabled = false;
                });
            },
            toggleTag(i) {
                let _this = this;
                let tag = _this.myTags[i];
                tag._isSel = !tag._isSel;
                _this.$set(_this.myTags, i, tag);
                _this.calculate();
            },
            calculate: function() {
                let _this = this;
                let len = _this.oldMyTags.length;
                if (_this.myTags.length !== len){
                    Toast("标签长度异常");
                    return ;
                }
                for (let i = 0; i < len; i++){
                    let oldChecked = _this.oldMyTags[i]._isSel;
                    let newChecked = _this.myTags[i]._isSel;
                    let tag_id = _this.oldMyTags[i].tag_id;
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
            }
        }
    }
</script>

<style scoped>

</style>