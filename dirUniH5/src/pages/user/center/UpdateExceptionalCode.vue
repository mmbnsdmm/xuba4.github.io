<template>
    <div class="user-center-update-exceptional-code">
        <u-gap></u-gap>
        <view class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <label>支付宝打赏二维码</label>
                    <u-gap></u-gap>
                    <image style="width: 200px; height: 200px;" mode="aspectFill" :src="alipay_exceptional_code?alipay_exceptional_code:QrcodePlaceholder" @tap="selectImage('alipay')"></image>
                    <u-gap></u-gap>
                    <label>微信打赏二维码</label>
                    <u-gap></u-gap>
                    <image style="width: 200px; height: 200px;" mode="aspectFill" :src="weixin_exceptional_code?weixin_exceptional_code:QrcodePlaceholder" @tap="selectImage('weixin')"></image>
                    <u-gap></u-gap>
                    <button class="btn btn-warning btn-block" @tap="init()">重置</button>
                    <button class="btn btn-danger btn-block" :disabled="isBtnDisabled" @tap="toUpdateExceptionalCode">确认修改</button>
                </div>
            </div>
        </view>
    </div>
</template>

<script>
    import {Toast} from 'vant'
    import {mapState} from 'vuex'
    export default {
        name: "UserCenterUpdateExceptionalCode",
        computed: {
            ...mapState(['userInfo'])
        },
        data() {
            return {
                QrcodePlaceholder: "https://via.placeholder.com/200x200?text=200x200",
                alipay_exceptional_code: "",
                alipay_exceptional_url: "",
                weixin_exceptional_code: "",
                weixin_exceptional_url: "",
                isBtnDisabled: false
            }
        },
        mounted(){
            this.init();
        },
        methods: {
            init: function () {
                let _this = this;
                _this.alipay_exceptional_code = _this.userInfo.alipay_exceptional_code;
                _this.weixin_exceptional_code = _this.userInfo.weixin_exceptional_code;
            },
            toUpdateExceptionalCode() {
                let _this = this;
                let formParams = {};
                if (!_this.alipay_exceptional_code){}else{
                    if (!/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/.test(_this.alipay_exceptional_code)){
                        Toast("支付宝打赏码路径不正确");
                        return ;
                    }
                    formParams.alipay_exceptional_code = _this.alipay_exceptional_code;
                    if (_this.alipay_exceptional_url) formParams.alipay_exceptional_url = _this.alipay_exceptional_url;
                }
                if (!_this.weixin_exceptional_code){}else{
                    if (!/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/.test(_this.weixin_exceptional_code)){
                        Toast("微信打赏码路径不正确");
                        return ;
                    }
                    formParams.weixin_exceptional_code = _this.weixin_exceptional_code;
                    if (_this.weixin_exceptional_url) formParams.weixin_exceptional_url = _this.weixin_exceptional_url;
                }
                _this.isBtnDisabled = true;
                _this.$auth.post('/user/center/update-exceptional-code', formParams, true, function (res) {
                    Toast(res.msg);
                    _this.isBtnDisabled = false;
                    _this.$auth.updateUserInfo();
                }, function (msg) {
                    Toast(msg);
                    _this.isBtnDisabled = false;
                    return ;
                })
            },
            selectImage(t) {
                let _this = this;
                uni.chooseImage({
                    count: 1, //默认9
                    sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], //从相册选择
                    success: (res) => {
                        let tempFilePaths = res.tempFilePaths;
                        tempFilePaths.forEach(function (temp) {
                            if (t === 'alipay') {
                                _this.$getImageData(temp, function (err, info) {
                                    let qrData = _this.$jsQR(info.data, info.width, info.height);
                                    if (qrData && qrData.data){
                                        _this.alipay_exceptional_url = qrData.data;
                                    }else{
                                        Toast("没有识别出正确的支付宝二维码");
                                    }
                                });
                            }
                            uni.uploadFile({
                                url : _this.$conf.apiUrl + '/user/file/upload',
                                filePath: temp,
                                name: 'ufile',
                                formData: _this.$auth.generateFormParams({}),
                                success: function (uploadFileRes) {
                                    let resp = JSON.parse(uploadFileRes.data);
                                    let urls = resp.data.urls;
                                    urls.forEach(function (url) {
                                        switch (t){
                                            case 'alipay':
                                                _this.alipay_exceptional_code = url;
                                                break;
                                            case 'weixin':
                                                _this.weixin_exceptional_code = url;
                                                break;
                                            default:
                                                Toast("未定义");
                                                break;
                                        }
                                    })
                                }
                            });
                        });
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>