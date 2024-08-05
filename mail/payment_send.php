<?php

use yii\helpers\Html;

?>
<p>
    <?='Estimado/a,'?>
</p>
<p>
    Se nos ha solicitado le enviamos el link de pago de licencias de <?=Yii::$app->params['app']['name']?>.
</p>
<p>
    <?=Html::a(Yii::t('stock', 'Begin payment'), $paymentLink, ['style' => '
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
        text-decoration: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        display: inline-block;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857;
        margin-bottom: 0;
        padding: 6px 12px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        box-sizing: border-box;', ])?>
</p>
<?=$this->render('_footer')?>
