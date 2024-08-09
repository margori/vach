<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment success');
?>
<div class="col-md-12">
    <div class="text-center">
        <div class="jumbotron">
            <?=Yii::$app->user->isGuest ? Html::img('@web/images/logo.png') . '<br><br>' : ''?>
            <h2 class="text-success"><?=Yii::t('payment', 'Payment successfull!')?></h2>
            <br>
            <br>
            <?php if (Yii::$app->user->isGuest) {
    echo Html::tag('h3', Yii::t('payment', 'Thank you!'), ['class' => 'text-success']);
} else {
    echo Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']);
}?>
            <?php if (Yii::$app->params['allow_invoicing']) {
    echo Html::a(Yii::t('stock', 'Get invoice'), ['payment/invoice', 'referenceCode' => $referenceCode], ['class' => 'btn btn-primary', 'target' => '_blank']);
}?>
        </div>
    </div>
</div>


