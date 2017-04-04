<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\slider\Slider;
use app\models\Stock;
use yii\web\View;

$this->title = Yii::t('stock', 'Buy Licences');

$this->registerJs("function updateTotal(quantity) {
    var quantity = $('#buymodel-quantity').val();
    var total = quantity * $model->price;
    $('#total').text('" . Yii::$app->formatter->numberFormatterSymbols[NumberFormatter::CURRENCY_SYMBOL] . "' + total.toFixed(2));
    return true;
    }", View::POS_END, 'refresh-page');
?>
<div class="col-md-12">
    <h1><?= $this->title ?></h1>
    <?php
    $form = ActiveForm::begin([
                'id' => 'buy-form',
                'options' => ['class' => 'form-inline'],
    ]);
    ?>
    <div class="text-center">
        <div class="col-sm-12">
            Su stock de licencias actual: <?= Yii::$app->formatter->asDecimal(Stock::getStock(1)) ?> licencias
            <hr>
        </div>
        <div class="col-sm-12">
            Precio por licencia: <?= Yii::$app->formatter->asCurrency($model->price) ?><br><br>
        </div>
        <?=
        $form->field($model, 'quantity', ['options' => [
                'class' => 'col-sm-push-4 col-sm-4',
                'onchange' => "updateTotal();"]
        ])
        ?>
        <div class="col-sm-12">
            Total:  <b><span id="total"><?= Yii::$app->formatter->asCurrency($model->price * $model->quantity) ?></span></b><br/><br>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-push-4 col-sm-4">
            <?= $form->field($model, 'product_id')->hiddenInput()->label('') ?>
            <?= Html::submitButton(\Yii::t('stock', 'Begin payment'), ['class' => 'btn btn-lg btn-success', 'name' => 'pay-button']) ?>         
        </div>
        <div class="clearfix"></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
