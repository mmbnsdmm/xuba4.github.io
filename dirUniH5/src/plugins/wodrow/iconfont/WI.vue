<template>
    <text class="wi" :style="{color: color, fontSize: fontSize}" v-html="type"></text>
</template>

<script>
    export default {
        name: "WI",
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
        font-family: 'wi';
        /** 阿里巴巴矢量图标库的字体库地址,你可以更换为你的图标库 **/
        src: url('http://at.alicdn.com/t/font_1840030_u7u5d9bjpki.ttf') format('truetype');
    }
    .wi {
        font-family: wi !important;
        font-size: 34rpx;
        padding: 4rpx;
    }
</style>