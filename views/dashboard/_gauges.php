<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP)
    $title = Yii::t('dashboard', 'Group Competence Matrix');
else if ($type == Wheel::TYPE_ORGANIZATIONAL)
    $title = Yii::t('dashboard', 'Organizational Competence Matrix');
else
    $title = Yii::t('dashboard', 'Individual Competence Matrix');

$token = rand(100000, 999999);
?>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
    <?php for ($i = 0; $i < 8; $i++) { ?>
        <div class="col-xs-4" >
            <?= $type == Wheel::TYPE_INDIVIDUAL ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_INDIVIDUAL, true) ?>
            <?= $type == Wheel::TYPE_INDIVIDUAL ? '</b>' : '' ?>
            -
            <?= $type == Wheel::TYPE_GROUP ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_GROUP, true) ?>
            <?= $type == Wheel::TYPE_GROUP ? '</b>' : '' ?>
            -
            <?= $type == Wheel::TYPE_ORGANIZATIONAL ? '<b>' : '' ?>
            <?= WheelQuestion::getDimentionName($i, Wheel::TYPE_ORGANIZATIONAL, true) ?>
            <?= $type == Wheel::TYPE_ORGANIZATIONAL ? '</b>' : '' ?>
            <?php
            if ($gauges[$i] > Yii::$app->params['good_consciousness'])
                $color = '5cb85c';
            else if ($gauges[$i] < Yii::$app->params['minimal_consciousness'])
                $color = 'd9534f';
            else
                $color = 'f0ad4e';
            $percentage = $gauges[$i] / 4 * 100;
            if ($percentage < 5)
                $width = 5;
            else
                $width = $percentage;
            ?>
            <div style='position:relative; color: white; font-size: 20px;' class="table table-bordered">
                <div style='font-size:0px; border-top: 28px solid #<?= $color ?>; width: <?= $width ?>%;'>&nbsp;</div>
                <div style='position:absolute; top:0px; left: 5px;'><?= floor($percentage) ?>%</div>
            </div>
        </div>
    <?php } ?>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
    <div class="col-md-12 text-center">
        <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
