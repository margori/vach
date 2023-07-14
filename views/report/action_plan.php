<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use app\controllers\ReportController;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('report', 'Summary Stage 2 and Conclusions');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('team', 'Teams'),
    'url' => ['/team'],
];
$this->params['breadcrumbs'][] = [
    'label' => $team->fullname,
    'url' => ['/team/view', 'id' => $team->id],
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('report', 'Report'),
    'url' => ['/report/view', 'id' => $team->id],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-technical">
    <h1>
        <?= Yii::t('report', 'Summary Stage 2 and Conclusions') ?>
    </h1>
    <div class="row col-md-12">
        <?php $form = ActiveForm::begin(['id' => 'report-form']); ?>
        <h3><?= Yii::t('report', 'Analysis') ?></h3>
        <p>
            <?= CKEditor::widget([
                'name' => 'analysis',
                'value' => $team->report->action_plan,
                'preset' => 'custom',
                'clientOptions' => ReportController::ANALYSIS_OPTIONS,
            ]) ?>
        </p>
        <div class="form-group">
            <?= Html::submitButton(\Yii::t('app', 'Save'), [
                'class' => 'btn btn-primary',
                'name' => 'save-button',
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>