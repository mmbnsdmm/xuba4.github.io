<template>
    <div class="about">
        <h2 class="block-title">This is an about page</h2>
        <van-search placeholder="请输入搜索关键词" />
        <van-button size="large" type="info" v-preventReClick>test</van-button>
    </div>
</template>

<script>
    export default {
        name: 'About',
        mounted: function () {}
    }
</script>