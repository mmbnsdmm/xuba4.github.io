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
                    create_type: _this.create_type
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
            }
        }
    }
</script>

<style scoped>

</style>