<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', 'Redirecting...');

$this->registerJs(
    "$(document).ready(function () { window.document.forms[0].submit(); });",
    View::POS_READY,
    'form-submit'
);
?>
<div class="col-md-12">
    <div class="text-center">
        <div class="jumbotron">
            <?=Yii::$app->user->isGuest ? Html::img('@web/images/logo.png') . '<br><br>' : ''?>
            <h3><?=Yii::t('app', 'Redirecting...')?></h3>
            <img src="images/red-loading.gif" />
        </div>
        <form method="post" action="<?=$buyModel->actionUrl?>">
            <input name="merchantId" type="hidden" value="<?=$buyModel->merchantId?>" >
            <input name="accountId" type="hidden" value="<?=$buyModel->accountId?>" >
            <input name="description" type="hidden" value="<?=$buyModel->description?>" >
            <input name="referenceCode" type="hidden" value="<?=$buyModel->referenceCode?>" >
            <input name="amount" type="hidden" value="<?=$buyModel->amount?>" >
            <input name="tax" type="hidden" value="<?=$buyModel->tax?>" >
            <input name="taxReturnBase" type="hidden" value="<?=$buyModel->taxReturnBase?>" >
            <input name="currency" type="hidden" value="<?=$buyModel->currency?>" >
            <input name="signature" type="hidden" value="<?=$buyModel->signature?>" >
            <input name="test" type="hidden" value="<?=$buyModel->test ? '1' : '0'?>" >
            <input name="buyerEmail" type="hidden" value="<?=$buyModel->buyerEmail?>" >
            <input name="responseUrl" type="hidden" value="<?=$buyModel->responseUrl?>" >
            <input name="confirmationUrl" type="hidden" value="<?=$buyModel->confirmationUrl?>" >
        </form>
    </div>
</div>
