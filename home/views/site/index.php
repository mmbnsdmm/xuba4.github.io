<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午4:10
 */
/**
 * @var \yii\web\View $this
 */
use kartik\helpers\Html;
use wodrow\yii2wtools\tools\JsBlock;
$this->title = "首页";
?>

<div class="site-index">
    test
</div>

<?php JsBlock::begin(); ?>
<script>
    var site_index = new Vue({
        el: ".site-index",
        data: {
            avatar: "/static/no_avatar.png",
        },
        methods: {
            updateAvatar: function () {
                alert(1);
            }
        },
        mounted: function () {
            // console.log(window.navigator.geolocation);
            window.navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude; //纬度
                var lag = position.coords.longitude; //经度
                console.log('纬度:'+lat+',经度:'+lag);
            });
        }
    });
</script>
<?php JsBlock::end(); ?>
