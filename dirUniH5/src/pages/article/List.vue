<template>
    <view class="article-list">
        <ol class="breadcrumb">
            <li>
                <text class="text-blue" @tap="$router.push('/')">首页</text>
            </li>
            <li class="active">文章列表</li>
            <text class="text-blue pull-right" @tap="$router.push('/pages/article/Add')">创建文章</text>
        </ol>
        <div class="row">
            <div class="col-xs-12">
                <WLoadMore ref="WODROW_LOAD_MORE" @provider="provider" :pageSize="page_size" color="#66ccff">
                    <template v-slot:list="{ items }"> <!-- 此处为插槽，只能使用template或其他自定义component -->
                        <view class="solid-top" v-for="(item,index) in items" :key="index">
                            <view class="item"> {{item.title}} </view>
                        </view>
                    </template>
                </WLoadMore>
            </div>
        </div>
    </view>
</template>

<script>
    import WLoadMore from '@/plugins/wodrow/list/LoadMore';
    import {Toast, Dialog} from 'vant'
    export default {
        name: "ArticleList",
        components: {
            WLoadMore
        },
        data() {
            return {
                page: 0,
                page_size: 10,
                total: 0,
                list: []
            }
        },
        methods: {
            provider(pd){
                let _this = this;
                setTimeout(function () {
                    let res = _this.getData(pd);
                    _this.$refs.WODROW_LOAD_MORE.pushData(res);
                }, 1000);
            },
            getData(pd) {
                console.log(pd);
                let _this = this;
                let res = [];
                let formParams = {
                    page: pd.pageNo,
                    page_size: pd.pageSize
                };
                _this.$auth.post("/article/default/list", formParams, true, function (r) {
                    _this.$_.forEach(r.list, function (v, k) {
                        res.push(v);
                    })
                }, function (msg) {
                    Toast(msg);
                });
                return res;
            },
            _getData(e){
                //模拟接口返回的第pageNo页的页数据
                let res = [];
                for(let i = 0; i < e.pageSize; i ++){
                    let s = {
                        name:this.pre+'_'+e.pageNo+'_'+i,
                    };
                    res.push(s);
                }
                //模拟请求数据失败
                if(!this.failFlag && e.pageNo === 3){
                    uni.showModal({
                        title: '模拟网络请求失败',
                        content: '模拟网络请求失败，未获取到数据\n再次上拉即可',
                        showCancel: false,
                        cancelText: '',
                        confirmText: '确定',
                        success: res => {},
                        fail: () => {},
                        complete: () => {}
                    });
                    res = null; // 失败时请传null
                    this.failFlag = true; // 清除模拟失败标志
                }
                //模拟尾页数据（尾页的数据可能一直在增加，但是不满一页）
                if(e.pageNo === 4 ){
                    uni.showModal({
                        title: '模拟请求尾页',
                        content: '清多次上拉模拟陆续有新数据加载',
                        showCancel: false,
                        cancelText: '',
                        confirmText: '确定',
                        success: res => {},
                        fail: () => {},
                        complete: () => {}
                    });
                    //模拟每次请求时，尾页新增了2条数据
                    if(this.count < e.pageSize){
                        this.count += 2; // 尾页总数据条数增加
                    }

                    //模拟尾页的数据
                    res = [];
                    for(let i=0;i<this.count;i++){
                        let s = {
                            name:this.pre+'_'+e.pageNo+'_'+i,
                        };
                        res.push(s);
                    }
                }
                //返回当前这一页的数据（成功返回数组，失败返回null）
                return res;
            }
        },
        onReady() {
            //如果是H5，请一定使用onReady方法初次加载数据，否则不会触发
            this.$refs.WODROW_LOAD_MORE.reLoadData()
        },
        onPullDownRefresh() {
            this.$refs.WODROW_LOAD_MORE.pullDownRefresh();
        },
        onReachBottom() {
            this.$refs.WODROW_LOAD_MORE.reachBottom()
        }
    }
</script>

<style scoped>
    .item{
        display: flex;
        justify-content: center;
        align-content: center;
        font-size: 60upx;
        line-height: 150upx;
        margin: 10upx 20upx;
        border-radius: 20upx;
        width: 710upx;
        height: 150upx;
        background: #CCCCCC;
    }
</style>