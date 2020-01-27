<template>
    <div class="site-test">
        <van-panel title="标题" desc="描述信息" status="状态">
            <div slot="default">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <van-image
                                    width="200"
                                    height="200"
                                    src="https://img.yzcdn.cn/vant/cat.jpeg"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div slot="footer">
                <van-button size="small">按钮</van-button>
                <van-button size="small" type="danger">按钮</van-button>
            </div>
        </van-panel>
    </div>
</template>

<script>
    export default {
        name: "Test",
        data: function(){
            return {
                show: false,
                fileList: []
            }
        },
        mounted: function () {
            // Dialog.confirm()
        },
        methods: {}
    }
</script>

<style scoped>

</style>