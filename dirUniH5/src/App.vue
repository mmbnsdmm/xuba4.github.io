<script>
	export default {
		onLaunch: function() {
            uni.onTabBarMidButtonTap(function () {
                console.log(123)
            })
		},
		onShow: function() {
			// console.log('App Show')
		},
		onHide: function() {
			// console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
    /* 解决头条小程序组件内引入字体不生效的问题 */
    /* #ifdef MP-TOUTIAO */
    @font-face {
    	font-family: uniicons;
    	src: url('/static/uni.ttf');
    }
    /* #endif */
    @import "uview-ui/index.scss";
    body {
        background: #ffffff !important;
    }
    .breadcrumb{
        margin-bottom: 0 !important;
    }
    uni-view{
        word-break:break-all;
    }
    .w-e-toolbar{
        flex-wrap:wrap !important;
    }
</style>
