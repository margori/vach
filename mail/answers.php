<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$current_dimension = -1;
$type_text = Wheel::getWheelTypes()[$wheel->type];
$dimensions = WheelQuestion::getDimensionNames($wheel->type);
?>
<h2>
    <?= Yii::t('wheel', 'These are your answers of ') . $type_text ?>
</h2>
<h3>
    <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
    <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
    <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    <?= Yii::t('app', 'Date') ?>: <?= Html::label($wheel->date) ?>
</h3>
<?php
foreach ($wheel->answers as $answer) {
    if ($current_dimension != $answer['dimension']) {
        $current_dimension = $answer['dimension'];
        echo '<h3>' . $dimensions[$current_dimension] . '</h3>';
    }
    ?>
    <p>
        <label class="control-label" for="loginmodel-email"><?= $questions[$answer['answer_order']]['question'] ?></label>
        <?php
        $answers_texts = WheelAnswer::getAnswerLabels($questions[$answer['answer_order']]['answer_type']);
        $answers_to_show = [];
        foreach ($answers_texts as $answer_value => $answer_text) {
            $answer_to_show = $answer_value == $answer['answer_value'] ? '<b>' : '';
            $answer_to_show .= $answer_text;
            $answer_to_show .= $answer_value == $answer['answer_value'] ? '</b>' : '';
            $answers_to_show[] = $answer_to_show;
        }
        echo implode(', ', $answers_to_show);
        ?>
    </p>
<?php } ?>
<hr />
<p>
    <?= Yii::t('wheel', 'Please, keep this email until the CPC process of your team is completed.') ?>
</p>
<p>
    <?= Yii::t('wheel', 'Thank you very much!') ?>
</p>
<?=$this->render('_footer')?>

