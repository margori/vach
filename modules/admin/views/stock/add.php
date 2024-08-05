<?php

use app\models\Product;
use app\models\User;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $model AddModel */

$this->title = Yii::t('stock', 'Add licences');

$this->params['breadcrumbs'][] = ['label' => Yii::t('stock', 'Licences'), 'url' => ['/admin/stock']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("function updateAmount(quantity) {
    var price = $('#addmodel-price').val();
    var quantity = $('#addmodel-quantity').val();
    var total = quantity * price;
    $('#total').text('" . Yii::$app->formatter->numberFormatterSymbols[NumberFormatter::CURRENCY_SYMBOL] . "' + total.toFixed(2));
    return true;
    }", View::POS_END, 'refresh-page');

$distributions = [
    1 => '50% ' . Yii::$app->params['part1_name'] . ' 50% ' . Yii::$app->params['part2_name'],
    2 => '100% ' . Yii::$app->params['part1_name'],
    3 => '100% ' . Yii::$app->params['part2_name'],
];
?>
<div class="col-md-12">
    <h1><?=$this->title?></h1>
    <?php $form = ActiveForm::begin([
    'id' => 'add-form',
]);
?>
    <?=$form->field($model, 'coach_id')->widget(Select2::classname(), ['data' => User::getUserList()])?>
    <?=$form->field($model, 'product_id')->dropDownList(Product::getList())?>
    <?=
$form->field($model, 'price', ['options' => [
    'onchange' => "updateAmount();"],
])
?>
    <?=$form->field($model, 'rate')?>
    <?=$form->field($model, 'quantity', ['options' => [
    'onchange' => "updateAmount();"],
])
?>
    <?=$form->field($model, 'part_distribution')->dropDownList($distributions)?>
    <?=$form->field($model, 'payed')->checkbox()?>
    <div class="form-group">
        Total:  <b><span id="total"><?=Yii::$app->formatter->asCurrency($model->price * $model->quantity)?></span></b>
    </div>
    <?=Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'name' => 'pay-button'])?>
    <?php ActiveForm::end();?>
</div>
