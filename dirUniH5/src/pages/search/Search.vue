<template>
    <view class="search-search">
        <view class="search-box">
            <!-- mSearch组件 如果使用原样式，删除组件元素-->
            <mSearch class="mSearch-input-box" :mode="2" button="inside" placeholder="关键字" @input="input" @search="doSearch" v-model.trim="keyword"></mSearch>
        </view>
        <view class="search-keyword" v-if="keywordListShow">
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
    </view>
</template>

<script>
    import mSearch from '@/plugins/mehaotian-search-revision/mehaotian-search-revision.vue';
    export default {
        name: "SearchSearch",
        data() {
            return {
                keyword: "",
                keywordListShow: true,
                maxOldKeywordCount: 20,
                oldKeywordList: [],
                hotKeywordList: ['键盘', '鼠标', '显示器', '电脑主机', '蓝牙音箱', '笔记本电脑', '鼠标垫', 'USB', 'USB3.0'],
                forbid: '',
                attentionSrc: '/static/search/attention.png'
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
        components: {
            mSearch
        },
        methods: {
            //执行搜索
            doSearch(keyword) {
                keyword = keyword===false?"":keyword;
                if (keyword){
                    this.keyword = keyword;
                    this.saveKeyword(keyword); //保存为历史
                    uni.showToast({
                        title: keyword,
                        icon: 'none',
                        duration: 2000
                    });
                    this.keywordListShow = false;
                }else{
                    uni.showToast({
                        title: "关键字必填",
                        icon: 'none',
                        duration: 2000
                    });
                }
            },
            input(v) {
                this.keywordListShow = true;
            },
            init() {
                this.loadOldKeyword();
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
                            // console.log('用户点击确定');
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
