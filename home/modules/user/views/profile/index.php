<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-13
 * Time: 上午9:44
 */
/**
 * @var \yii\web\View $this
 */
$x = Yii::$app->apiTool->post('/test/default/test1', ['test' => "ttt"], Yii::$app->user->identity);
?>

<div class="user-profile-index">
    <div class="row">
        <div class="col-lg-12">
            profile
        </div>
    </div>
</div>
