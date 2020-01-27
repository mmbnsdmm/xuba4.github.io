<template>
    <div class="about">
        <h2 class="block-title">This is an about page</h2>
        <van-search placeholder="请输入搜索关键词" />
    </div>
</template>

<script>
    // import {Dialog} from 'vant'
    export default {
        name: 'About',
        mounted: function () {
            // Dialog.confirm({
            //     title: "版本强制更新",
            //     message: "强制更新，老版本必须下载更新"
            // }).then(() => {
            //     this.$tool.log(2);
            // }).catch(() => {
            //     this.$tool.log(1);
            // });
        }
    }
</script>