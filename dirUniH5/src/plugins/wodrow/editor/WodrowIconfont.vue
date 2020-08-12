<template>
    <text class="we-icon" :style="{color: color, fontSize: fontSize}" v-html="type"></text>
</template>

<script>
    export default {
        name: "WodrowIconfont",
        props: {
            type: {
                type: String,
                default: '&#xe659;'
            },
            color: {
                type: String,
                default: '#666666'
            },
            fontSize: {
                type: String,
                default: '34rpx'
            }
        },
        methods: {}
    }
</script>

<style scoped>
    @font-face {
        font-family: 'wodrowe';
        /** 阿里巴巴矢量图标库的字体库地址,你可以更换为你的图标库 **/
        src: url('http://at.alicdn.com/t/font_1996238_my5aw82c4nm.ttf') format('truetype');
    }
    .we-icon {
        font-family: wodrowe !important;
        font-size: 34rpx;
        padding: 4rpx;
    }
</style>