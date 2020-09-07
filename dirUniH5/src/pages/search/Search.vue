<template>
    <view class="search-search">
        <view class="search-box">
            <!-- mSearch组件 如果使用原样式，删除组件元素-->
            <mSearch class="mSearch-input-box" :mode="2" button="inside" :placeholder="placeholder" @input="input" @search="doSearch" v-model.trim="keyword"></mSearch>
        </view>
        <view class="search-keyword" v-show="keywordListShow">
            <scroll-view class="keyword-box" scroll-y>
                <view class="keyword-block" v-if="oldKeywordList.length > 0">
                    <view class="keyword-list-header">
                        <view>历史搜索</view>
                        <view>
                            <image @tap="oldDelete" src="/static/search/delete.png"></image>
                        </view>
                    </view>
                    <view class="keyword">
                        <view v-for="(keyword,index) in oldKeywordList" @tap="doSearch(keyword)" :key="index">{{keyword}}</view>
                    </view>
                </view>
                <view class="keyword-block">
                    <view class="keyword-list-header">
                        <view>热门搜索</view>
                        <view>
                            <image @tap="hotToggle" :src="attentionSrc"></image>
                        </view>
                    </view>
                    <view class="keyword" v-if="forbid==''">
                        <view v-for="(keyword,index) in hotKeywordList" @tap="doSearch(keyword)" :key="index">{{keyword}}</view>
                    </view>
                    <view class="hide-hot-tis" v-else>
                        <view>当前搜热门搜索已隐藏</view>
                    </view>
                </view>
            </scroll-view>
        </view>
        <view class="container-fluid">
            <div class="row" v-show="!keywordListShow">
                <div class="col-xs-12">
                    <WLoadMore ref="WODROW_LOAD_MORE_SEARCH_LIST" @provider="provider" :pageSize="page_size" color="#66ccff">
                        <template v-slot:list="{items}">
                            <view class="solid-top" v-for="(item, index) in items" :key="index">
                                <u-card padding="10" margin="15rpx" :border="false" :show-head="false" :foot-border-top="false" title-size="15rpx"
                                        @body-click="toView(item.type, item.type_model_id)">
                                    <view class="" slot="body">
                                        <view>
                                            <text class="text-blue" style="font-size: 36rpx" v-html="item.title"></text>
                                        </view>
                                    </view>
                                    <view class="" slot="foot">
                                        <text class="text-green">{{$conf.serverData.enums.searchIndex.typeDesc[item.type]}}</text>
                                        <u-icon name="eye-fill" size="34" color="" label="查看" class="pull-right text-blue" @tap="toView(item.type, item.type_model_id)"></u-icon>
                                        <div class="clearfix"></div>
                                    </view>
                                </u-card>
                            </view>
                        </template>
                    </WLoadMore>
                </div>
            </div>
            <ScrollTopIcon @tapIcon="tapIcon" v-show="!keywordListShow"></ScrollTopIcon>
        </view>
    </view>
</template>

<script>
    import mSearch from '@/plugins/mehaotian-search-revision/mehaotian-search-revision.vue';
    import WLoadMore from '@/plugins/wodrow/list/LoadMore';
    import ScrollTopIcon from '@/plugins/wodrow/list/ScrollTopIcon';
    import {Toast} from 'vant';
    export default {
        name: "SearchSearch",
        components: {
            mSearch,
            WLoadMore,
            ScrollTopIcon
        },
        data() {
            return {
                placeholder: "",
                keyword: "",
                keywordListShow: true,
                maxOldKeywordCount: 20,
                oldKeywordList: [],
                hotKeywordList: ['search@All'],
                forbid: '',
                attentionSrc: '/static/search/attention.png',
                page: 0,
                page_size: 10,
                total: 0,
                list: []
            }
        },
        onLoad(options) {
            let _this = this;
            _this.init();
            if (options.keyword) _this.keyword = options.keyword;
            if (_this.keyword) {
                this.doSearch(_this.keyword);
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE_SEARCH_LIST.reLoadData();
        },
        onPullDownRefresh() {
            if (!this.keywordListShow) {
                this.$refs.WODROW_LOAD_MORE_SEARCH_LIST.pullDownRefresh();
            }else{
                setTimeout(function () {
                    uni.stopPullDownRefresh();
                }, 1000);
            }
        },
        onReachBottom() {
            if (!this.keywordListShow) this.$refs.WODROW_LOAD_MORE_SEARCH_LIST.reachBottom();
        },
        methods: {
            //执行搜索
            doSearch(keyword) {
                keyword = keyword===false?"":keyword;
                if (keyword){
                    this.keyword = keyword;
                    this.saveKeyword(keyword); //保存为历史
                    this.keywordListShow = false;
                    this.$refs.WODROW_LOAD_MORE_SEARCH_LIST.reLoadData();
                }else{
                    uni.showToast({
                        title: "关键字必填",
                        icon: 'none',
                        duration: 2000
                    });
                    this.keywordListShow = true;
                }
            },
            input(v) {
                this.keywordListShow = true;
            },
            init() {
                this.loadOldKeyword();
                this.placeholder = "关键字，想要查看全部请输入: " + this.$conf.serverData.datas.searchAllKeyword;
            },
            blur(){
                uni.hideKeyboard()
            },
            //加载历史搜索,自动读取本地Storage
            loadOldKeyword() {
                uni.getStorage({
                    key: 'OldKeys',
                    success: (res) => {
                        this.oldKeywordList = JSON.parse(res.data);
                    }
                });
            },
            //清除历史搜索
            oldDelete() {
                uni.showModal({
                    content: '确定清除历史搜索记录？',
                    success: (res) => {
                        if (res.confirm) {
                            this.oldKeywordList = [];
                            uni.removeStorage({
                                key: 'OldKeys'
                            });
                        } else if (res.cancel) {
                            // console.log('用户点击取消');
                        }
                    }
                });
            },
            //热门搜索开关
            hotToggle() {
                this.forbid = this.forbid ? '' : '_forbid';
                this.attentionSrc = '/static/search/attention' + this.forbid + '.png'
            },
            //保存关键字到历史记录
            saveKeyword(keyword) {
                uni.getStorage({
                    key: 'OldKeys',
                    success: (res) => {
                        let OldKeys = JSON.parse(res.data);
                        let findIndex = OldKeys.indexOf(keyword);
                        if (findIndex === -1) {
                            OldKeys.unshift(keyword);
                        } else {
                            OldKeys.splice(findIndex, 1);
                            OldKeys.unshift(keyword);
                        }
                        OldKeys.length > this.maxOldKeywordCount && OldKeys.pop();
                        uni.setStorage({
                            key: 'OldKeys',
                            data: JSON.stringify(OldKeys)
                        });
                        this.oldKeywordList = OldKeys; //更新历史搜索
                    },
                    fail: (e) => {
                        let OldKeys = [keyword];
                        uni.setStorage({
                            key: 'OldKeys',
                            data: JSON.stringify(OldKeys)
                        });
                        this.oldKeywordList = OldKeys; //更新历史搜索
                    }
                });
            },
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE_SEARCH_LIST.pushData(res);
                }, 1000);
            },
            getData(pd) {
                let _this = this;
                let res = [];
                let formParams = {
                    keyword: _this.keyword,
                    page: pd.pageNo,
                    page_size: pd.pageSize
                };
                _this.$auth.post("/search/index/list", formParams, false, function (r) {
                    _this.$_.forEach(r.list, function (v, k) {
                        if (_this.keyword !== _this.$conf.serverData.datas.searchAllKeyword) v.title = v.title.replace(_this.keyword, "<b>" + _this.keyword + "</b>");
                        res.push(v);
                    });
                }, function (msg) {
                    Toast(msg);
                });
                return res;
            },
            tapIcon(e){
                uni.pageScrollTo({
                    duration:60,
                    scrollTop:0
                })
            },
            toView(type, type_model_id) {
                let url = "";
                switch (type){
                    case 1:
                        url = "/pages/user/profile/Index?id=" + type_model_id;
                        break;
                    case 2:
                        url = "/pages/article/View?id=" + type_model_id;
                        break;
                    case 3:
                        url = "/pages/tag/View?id=" + type_model_id;
                        break;
                    default:
                        Toast("未定义搜索索引类型，请联系管理员");
                        break;
                }
                uni.navigateTo({
                    url: url
                });
            }
        }
    }
</script>
<style scoped>
    view{display:block;}
    .search-box {width:100%;background-color:rgb(242,242,242);padding:15upx 2.5%;display:flex;justify-content:space-between;position:sticky;top: 0;}
    .search-box .mSearch-input-box{width: 100%;}
    .search-box .input-box {width:85%;flex-shrink:1;display:flex;justify-content:center;align-items:center;}
    .search-box .search-btn {width:15%;margin:0 0 0 2%;display:flex;justify-content:center;align-items:center;flex-shrink:0;font-size:28upx;color:#fff;background:linear-gradient(to right,#ff9801,#ff570a);border-radius:60upx;}
    .search-box .input-box>input {width:100%;height:60upx;font-size:32upx;border:0;border-radius:60upx;-webkit-appearance:none;-moz-appearance:none;appearance:none;padding:0 3%;margin:0;background-color:#ffffff;}
    .placeholder-class {color:#9e9e9e;}
    .search-keyword {width:100%;background-color:rgb(242,242,242);}
    .keyword-list-box {height:calc(100vh - 110upx);padding-top:10upx;border-radius:20upx 20upx 0 0;background-color:#fff;}
    .keyword-entry-tap {background-color:#eee;}
    .keyword-entry {width:94%;height:80upx;margin:0 3%;font-size:30upx;color:#333;display:flex;justify-content:space-between;align-items:center;border-bottom:solid 1upx #e7e7e7;}
    .keyword-entry image {width:60upx;height:60upx;}
    .keyword-entry .keyword-text,.keyword-entry .keyword-img {height:80upx;display:flex;align-items:center;}
    .keyword-entry .keyword-text {width:90%;}
    .keyword-entry .keyword-img {width:10%;justify-content:center;}
    .keyword-box {height:calc(100vh - 110upx);border-radius:20upx 20upx 0 0;background-color:#fff;}
    .keyword-box .keyword-block {padding:10upx 0;}
    .keyword-box .keyword-block .keyword-list-header {width:100%;padding:10upx 3%;font-size:27upx;color:#333;display:flex;justify-content:space-between;}
    .keyword-box .keyword-block .keyword-list-header image {width:40upx;height:40upx;}
    .keyword-box .keyword-block .keyword {width:94%;padding:3px 3%;display:flex;flex-flow:wrap;justify-content:flex-start;}
    .keyword-box .keyword-block .hide-hot-tis {display:flex;justify-content:center;font-size:28upx;color:#6b6b6b;}
    .keyword-box .keyword-block .keyword>view {display:flex;justify-content:center;align-items:center;border-radius:60upx;padding:0 20upx;margin:10upx 20upx 10upx 0;height:60upx;font-size:28upx;background-color:rgb(242,242,242);color:#6b6b6b;}
</style>
