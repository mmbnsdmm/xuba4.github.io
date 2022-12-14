<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午5:36
 */
/**
 * @var \home\models\FormSignup $model
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use wodrow\yii2wtools\tools\JsBlock;

$this->title = "注册";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup" v-cloak>
    <div class="row">
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(); ?>
            <?=$form->field($model, 'email')->textInput(['v-model' => "email"]) ?>
            <?=$form->field($model, 'username')->textInput() ?>
            <?=$form->field($model, 'password')->passwordInput() ?>
            <?=$form->field($model, 'repassword')->passwordInput() ?>
            <?=$form->field($model, 'signup_message')->textarea() ?>
            <?=$form->field($model, 'code')->textInput(['placeholder' => "验证码"]) ?>
            <?=Html::button("发送验证码{{count}}", ['class' => "btn btn-default", '@click' => "sendCode", ':disabled'=>"isDisabled"]) ?>
            <?=Html::submitButton("注册", ['class' => "btn btn-primary"]) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php JsBlock::begin(); ?>
    <script>
        let signup = new Vue({
            el: ".site-signup",
            data: {
                email: "<?=$model->email ?>",
                count: "",
                isDisabled: false,
            },
            methods: {
                sendCode:function () {
                    let _this = this;
                    let email_reg = VueBody.regs.email;
                    if (!email_reg.test(this.email)){
                        alert("邮箱错误");
                        return ;
                    }
                    $.ajax({
                        url: "<?=Yii::$app->apiTool->getFullUrl('/site/send-email-code') ?>",
                        type: "post",
                        data: {
                            email: this.email,
                            typeKey: 1
                        },
                        dataType: "json",
                        success: function (msg) {
                            if (msg.code == 200){
                                if (msg.data.status == 200){
                                    alert("发送成功");
                                    _this.count = 120;
                                    _this.isDisabled = true;
                                    let timer_sendCode = setInterval(() => {
                                        _this.count--;
                                        if (_this.count <= 0) {
                                            _this.count = '';
                                            _this.isDisabled = false;
                                            clearInterval(timer_sendCode);
                                        }
                                    }, 1000);
                                }else{
                                    alert(msg.data.msg);
                                }
                            }else{
                                alert(msg.message);
                            }
                        }
                    });
                }
            }
        });
    </script>
<?php JsBlock::end(); ?>