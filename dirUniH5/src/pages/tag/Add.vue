<template>
    <div class="tag-add">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li>
                <text class="text-blue" @tap="$router.push('/pages/tag/List')">圈子列表</text>
            </li>
            <li class="active">创建圈子</li>
        </ol>
        <view class="tag-add-form">
            <u-field v-model="name" label="标题" placeholder="请填写标题"></u-field>
            <u-gap></u-gap>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <button class="btn btn-primary btn-block" :disabled="isBtnDisabled" @tap="toCreate">创建</button>
                    </div>
                    <div class="col-xs-12">
                        <div class="help-block">
                            <div>如果圈子已存在请在圈子列表加入</div>
                        </div>
                    </div>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant';
    export default {
        name: "TagAdd",
        data() {
            return {
                name: "",
                isBtnDisabled: false
            }
        },
        methods: {
            toCreate() {
                let _this = this;
                if (!_this.name){
                    Toast("名称不能为空");
                    return;
                }
                if (_this.name.length < 2){
                    Toast("内容必须不少于2个字符");
                    return;
                }
                if (_this.name.length > 50){
                    Toast("内容必须不多于50个字符");
                    return;
                }
                _this.isBtnDisabled = true;
                let formParams = {
                    name: _this.name
                };
                _this.$auth.post('/tag/default/add', formParams, true, function (res) {
                    Toast(res.msg);
                    _this.isBtnDisabled = false;
                    let tag = res.tag;
                    uni.redirectTo({
                        url: "/pages/user/center/MyTag"
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