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
        el: ".site-index"
    });
</script>
<?php JsBlock::end(); ?>
