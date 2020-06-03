<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-25
 * Time: 下午5:36
 */
/**
 * @var \home\models\FormResetPassword $model
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use wodrow\yii2wtools\tools\JsBlock;

$this->title = "重置密码";
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="site-reset-password" v-cloak>
        <div class="row">
            <div class="col-sm-5">
                <?php $form = ActiveForm::begin(); ?>
                <?=$form->field($model, 'email')->textInput(['v-model' => "email", 'type' => "email"]) ?>
                <?=$form->field($model, 'password')->passwordInput() ?>
                <?=$form->field($model, 'repassword')->passwordInput() ?>
                <?=$form->field($model, 'code')->textInput(['placeholder' => "验证码"]) ?>
                <?=Html::button("发送验证码{{count}}", ['class' => "btn btn-default", '@click' => "sendCode", ':disabled'=>"is_disabled"]) ?>
                <?=Html::submitButton("重置密码", ['class' => "btn btn-primary"]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


<?php JsBlock::begin(); ?>
    <script>
        let reset_password = new Vue({
            el: ".site-reset-password",
            data: {
                email: "<?=$model->email ?>",
                count: "",
                is_disabled: false,
            },
            methods: {
                sendCode:function () {
                    let _this = this;
                    let email_reg = vue_body.reg_email;
                    if (!email_reg.test(this.email)){
                        alert("邮箱地址错误");
                        return ;
                    }
                    $.ajax({
                        url: "<?=Yii::$app->apiTool->baseUri ?>/site/send-email-code",
                        type: "post",
                        data: {
                            email: this.email,
                            typeKey: 3,
                        },
                        dataType: "json",
                        success: function (msg) {
                            if (msg.code == 200){
                                if (msg.data.is_ok == 1){
                                    alert("发送成功");
                                    _this.count = 120;
                                    _this.is_disabled = true;
                                    let timer_sendCode = setInterval(() => {
                                        _this.count--;
                                        if (_this.count <= 0) {
                                            _this.count = '';
                                            _this.is_disabled = false;
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