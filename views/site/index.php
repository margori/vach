<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::$app->params['app']['name'];
?>
<div class="site-index">
    <div class="jumbotron">
        <?=Html::img('@web/images/logo.png')?>
        <?php if (Yii::$app->params['server_status'] != 'online') {?>
            <h5 class="text-info">
                <?=Yii::$app->params['server_status']?>
            </h5>
        <?php }?>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-xs-push-2 col-xs-8 col-sm-push-2 col-sm-8 col-md-push-4 col-md-4 ">
                <?=app\widgets\Alert::widget()?>
                <?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => ['login'],
]);?>
                <?=$form->field($model, 'username')?>
                <?=$form->field($model, 'password')->passwordInput()?>
                <div class="form-group">
                    <?=\app\components\SpinnerSubmitButton::widget([
    'caption' => Yii::t('app', 'Sign in'),
    'options' => [
        'name' => 'login-button',
        'class' => 'btn btn-default col-md-12',
    ],
])?>
                    <br/>
                </div>
                <div class="text-right form-group" style="margin-top: 20px;">
                    <?=Yii::$app->params['allow_register']
? Html::a(
    Yii::t('app', 'Sign up'),
    ['site/register'],
    ['class' => 'btn btn-vach-border pull-left']
)
: ''?>
                    <?=Html::a(
    Yii::t('app', 'Reset password'),
    ['site/request-password-reset'],
    ['class' => 'text-danger']
)?>
                </div>
                <?php ActiveForm::end();?>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-push-3 col-xs-6 col-sm-push-4 col-sm-3 col-md-push-4 col-md-4 text-center"
                 style="margin-top: 30px;">
                <?php $wheelForm = ActiveForm::begin([
    'id' => 'token-form',
    'action' => ['token'],
]);?>
                <?=$wheelForm->field($wheel, 'token')?>
                <div class="form-group">
                    <?=\app\components\SpinnerSubmitButton::widget([
    'caption' => Yii::t('app', 'Run'),
    'options' => [
        'name' => 'run-button',
        'class' => 'btn btn-vach-border col-md-12',
    ],
])?>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
