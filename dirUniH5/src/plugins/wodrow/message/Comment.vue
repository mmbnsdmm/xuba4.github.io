<template>
    <view class="mask" :class="maskState?'show':'none'" @click="toggleMask">
        <view class="mask-content" @click.stop.prevent="stopPrevent">
            <view class="mask-content-topbar">
                <view class="left" @click="toggleMask">取消</view>
                <view class="right" @click="publishComment">发布</view>
            </view>
            <view class="mask-content-input">
				<textarea class="textarea"
                    v-model="content"
                    :placeholder="placeholder"
                    :cursor-spacing = "100"
                    :show-confirm-bar = "false"
                    :focus="focus"
                    :maxlength="maxStrLen"></textarea>
            </view>
        </view>
    </view>
</template>

<script>
    import {Toast} from 'vant'
    export default {
        name: "WComment",
        //属性
        props: {
            placeholder: {
                type: String,
                default: "请输入..."
            },
            minStrLen: {
                type: Number,
                default: 0
            },
            maxStrLen: {
                type: Number,
                default: 65535
            }
        },
        data() {
            return {
                maskState: false,
                content: "",
                focus: false
            };
        },
        created() {
        },
        methods: {
            stopPrevent(){},
            toggleMask(type){
                let	state = type === 'show';
                setTimeout(()=>{
                    this.maskState = state;
                    // #ifdef APP-PLUS
                    // 安卓app软键盘自动弹出有点问题，暂时还没有很好的解决方案，所以就禁止安卓app软键盘自动弹出，如果哪位朋友有好的解决方案可以在评论里告诉大家参考一下
                    if (uni.getSystemInfoSync().platform === "ios") {
                        this.focus = this.maskState;
                    }
                    // #endif
                    // #ifndef APP-PLUS
                    this.focus = this.maskState;
                    // #endif
                }, 300)
            },
            publishComment() {
                if (this.content.length < this.minStrLen){
                    Toast("字符数不能少于" + this.minStrLen);
                    return;
                }
                if (this.content.length > this.maxStrLen){
                    Toast("字符数不能多于" + this.minStrLen);
                    return;
                }
                this.$emit('publishComment', this.content);
            }
        }
    }
</script>

<style lang="scss" scoped>
    $font-color-base: #606266;
    $base-color: #5A9BEC;
    .mask{
        display: flex;
        align-items: flex-end;
        position: fixed;
        left: 0;
        top: var(--window-top);
        bottom: 0;
        width: 100%;
        background: rgba(0,0,0,0);
        z-index: 9995;
        transition: .3s;
        -webkit-transition: .3s;
        .mask-content{
            width: 100%;
            background: #FFFFFF;
            transform: translateY(100%);
            transition: .3s;//底部弹出的持续时间
            -webkit-transition: .3s;//底部弹出的持续时间
            // overflow-y:scroll;
            display: flex;
            flex-direction: column;
            .mask-content-topbar{
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                padding: 20upx 16upx 10upx;
                font-size: 32upx;
                .left{
                    padding: 10upx 0upx;
                    color: $font-color-base;
                }
                .right{
                    padding: 10upx 100upx;
                    border-radius: 6upx;
                    color: #FFFFFF;
                    font-weight: 500;
                    background-color: $base-color;
                }
            }
            .mask-content-input{
                /*width: 718upx;//如果textarea的宽带有问题可以把width改为100%试试*/
                width: 100%;
                padding: 10upx 16upx 20upx;
                .textarea {
                    height: 100px;
                    /*width: 686upx;//如果textarea的宽带有问题可以把width改为100%试试*/
                    width: 100%;
                    padding: 16upx;
                    border:2upx solid #d5d5d6;
                    border-radius: 8upx;
                }
            }
        }
        &.none{
            display: none;
        }
        &.show{
            background: rgba(0,0,0,.4);
            .mask-content{
                transform: translateY(0);
            }
        }
    }
    .show{
        display: flex !important;
    }
</style>
