<template>
    <div class="home">
        <view class="content">
            <navigator url="/pages/site/About">
                <button class="btn btn-primary">about</button>
            </navigator>
        </view>
    </div>
</template>

<script>
    export default {
        name: 'SiteHome',
        mounted: function() {
            let _this = this;
            _this.$_.forEach({a: "test", b: 123}, function (v, k) {
                console.log(v)
            });
            let x = _this.$moment().format('LLL');
            console.log(x)
        },
        data() {
            return {};
        }
    }
</script>
