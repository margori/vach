<?php

use yii\helpers\Html;
?>
<div class="container">
    <p class="pull-left">
        <?=Html::a('Español', ['site/es'])?>
        &nbsp;
        <?=Html::a('English', ['site/en'])?>
        &nbsp;
        <?=Html::a(Yii::t('feedback', 'Feedbacks'), ['/feedback'])?>
        &nbsp;
        <?=Html::a(Yii::t('app', 'Consults'), ['/site/contact'])?>
    </p>
    <p class="pull-right">
        <?=Html::a(Yii::t('app', 'Source code'), 'https://github.com/fundacionempowerment/VACH', ['rel' => 'external', 'target' => '_blank'])?>
        &nbsp;
        <?=Yii::t('app', 'Powered by')?>
        <?=Html::a('Yii Framework', 'http://www.yiiframework.com/', ['rel' => 'external', 'target' => '_blank'])?>
    </p>
</div>