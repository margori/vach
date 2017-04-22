<?php

use yii\helpers\Html;
?>

<h3 class="text-danger"><?= Yii::t('payment', 'There was an error') ?></h3>
<h3><?= Yii::t('payment', 'VACH administrators has been notified.') ?></h3>
<h3><?= Yii::t('payment', 'We are going to contact you soon.') ?></h3>
<h4><?= Yii::t('app', 'or') ?></h4>
<?= Html::a(Yii::t('app', 'Contact administrator'), ['site/contact'], ['class' => 'btn btn-primary']) ?>
