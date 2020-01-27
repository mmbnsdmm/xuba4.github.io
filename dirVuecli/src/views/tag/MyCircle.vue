<template>
    <div class="tag-circle">
        <van-search v-model="searchValue" placeholder="请输入搜索关键词" shape="round" @search="search" show-action>
            <div slot="action" @click="search">搜索</div>
        </van-search>
        <h2 class="block-title">搜索框键入<code>{{searchAllValue}}</code>会列出所有圈子,如果没有找到圈子,你可以<van-button size="mini" type="primary" to="/tag/add-circle">创建圈子</van-button></h2>
        <van-list  v-model="loading" :finished="finished" finished-text="没有更多了" @load="onLoad">
            <van-cell v-for="item in list" :key="item.id" :label="item.label" :is-link="item.isYouJoin" :to="item.isYouJoin?item.to:''">
                <template slot="title">
                    <span class="">{{item.name}}</span>
                </template>
                <template slot="default">
                    <van-tag v-if="item.isYouJoin" @click.stop="quit(item.id)">退出</van-tag>
                    <van-tag type="danger" v-if="item.isYours" @click.stop="del(item.id)">删除</van-tag>
                    <van-tag type="primary" v-if="!item.isYouJoin" @click.stop="join(item.id)">加入</van-tag>
                </template>
            </van-cell>
        </van-list>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "MyCircle",
        mounted: function () {
            let _this = this;
            _this.search();
        },
        data: function() {
            return {
                user: this.$store.getters.user,
                searchValue: "",
                searchAllValue: ":@all",
                list: [],
                listIndex: [],
                index: 0,
                loading: false,
                finished: true
            }
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
                _this.listIndex = cacheList.filter((v) => {
                    if (_this.searchValue === ""){
                        return v.isYouJoin;
                    }else if(_this.searchValue === _this.searchAllValue){
                        return true;
                    }else{
                        return v.name.search(_this.searchValue) > -1;
                    }
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
                        if (process.env.VUE_APP_ENV === "dev") _this.$tool.delCache("circle_info_tag_id_" + indexInfo.id); // 测试
                        let tagCache = _this.$tool.getCache("circle_info_tag_id_" + indexInfo.id);
                        if (!tagCache){
                            let formData = {id: indexInfo.id};
                            let data = _this.$auth.authPost("/tag/circle/info", formData);
                            if (!data){
                                _this.index = i;
                                _this.loading = false;
                                _this.finished = true;
                                return ;
                            }
                            tagCache = data.tag;
                            _this.$tool.setCache("circle_info_tag_id_" + indexInfo.id, tagCache, 300);
                        }
                        let tagInfo = tagCache;
                        tagInfo.isYours = tagInfo.created_by - _this.user.id === 0;
                        tagInfo.to = "/tag/info-circle/" + tagInfo.id;
                        tagInfo.label = "成员:" + tagInfo.userTotal + ";文章:" + tagInfo.articleTotal;
                        _this.list.push(tagInfo);
                    }
                    this.index = i;
                    this.loading = false;
                }, 500);
            },
            join: function(id) {
                let _this = this;
                let data = _this.$auth.authPost("/tag/circle/join", {tag_id: id});
                if (data.is_ok === 1){
                    _this.$tool.delCache("circle_info_tag_id_" + id);
                    _this.$tool.delCache("circle_list");
                }
                Toast(data.msg);
                // _this.$router.push("/tag/info-circle/" + id);
                _this.search();
            },
            quit: function (id) {
                let _this = this;
                let data = _this.$auth.authPost("/tag/circle/quit", {tag_id: id});
                if (data.is_ok === 1){
                    _this.$tool.delCache("circle_info_tag_id_" + id);
                    _this.$tool.delCache("circle_list");
                }
                Toast(data.msg);
                _this.search();
            },
            del: function (id) {
                let _this = this;
                let data = _this.$auth.authPost("/tag/circle/del", {tag_id: id});
                if (data.is_ok === 1){
                    _this.$tool.delCache("circle_info_tag_id_" + id);
                    _this.$tool.delCache("circle_list");
                }
                Toast(data.msg);
                _this.search();
            }
        }
    }
</script>

<style>

</style>