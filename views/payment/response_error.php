<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment error');
?>
<div class="col-md-12">    
    <div class="text-center">
        <div class="jumbotron">
            <h3 class="text-danger"><?= Yii::t('payment', 'There was an error') ?></h3>
            <h3><?= Yii::t('payment', 'Site administrators has been notified.') ?></h3>
            <h3><?= Yii::t('payment', 'We are going to contact you soon.') ?></h3>
            <h4><?= Yii::t('app', 'or') ?></h4>
            <?= Html::a(Yii::t('app', 'Contact administrator'), ['site/contact'], ['class' => 'btn btn-primary']) ?>
            <br>
            <br>
            <?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>
        </div>        
    </div>
</div>

