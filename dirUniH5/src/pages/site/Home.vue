<template>
    <div class="home">
        <view class="container">
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
        },
        data() {
            return {};
        }
    }
</script>
