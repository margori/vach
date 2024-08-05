<?php

use app\widgets\Alert;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('register', 'Sign up');
?>
<div class="site-register">
    <?=Html::img('@web/images/logo.png', ['class' => 'image-responsive', 'height' => '35px'])?>
    <h1><?=Html::encode($this->title)?></h1>
    <?=Alert::widget()?>
    <p><?=Yii::t('register', 'Please, fill your sign up form out:')?></p>

    <?php $form = ActiveForm::begin(['id' => 'register-form']);?>
    <?=$form->field($model, 'name')?>
    <?=$form->field($model, 'surname')?>
    <?=$form->field($model, 'email')?>
    <?=$form->field($model, 'phone')?>
    <?=$form->field($model, 'username')?>
    <?=$form->field($model, 'password')->passwordInput()?>
    <?=$form->field($model, 'password_confirm')->passwordInput()?>
    <div class="form-group">
        <?=Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary', 'name' => 'register-button'])?>
    </div>
    <?php ActiveForm::end();?>
</div>
