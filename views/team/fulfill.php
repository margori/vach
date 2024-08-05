<?php

use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
/* @var $team app\models\Team */

$this->title = $team->id == 0 ? Yii::t('team', 'New team') : $team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $team->fullname, 'url' => ['/team/view', 'id' => $team->id]];
$this->params['breadcrumbs'][] = $this->title;

$lock_button = Yii::$app->params['monetize'] && $licences_to_buy > 0;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-12">
        <h4><?= Yii::t('team', 'Team data') ?> </h4>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?><br/>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?><br/>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?>
        </p>
    </div>
    <div class="row col-md-12">
        <h4><?= Yii::t('team', 'Members') ?></h4>
        <?php
        $membersDataProvider = new ArrayDataProvider([
            'allModels' => $team->members,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $membersDataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'member.fullname',
                ],
            ],
        ]);
        ?>
    </div>

    <?php if (Yii::$app->params['monetize']) { ?>
        <div class="form-group">
            <h4><?= Yii::t('stock', 'Licences') ?></h4>
            <p>
                <?= Yii::t('stock', 'Your balance of {licence type}', [
                    'licence type' => $team->teamType->product->name
                ]) ?>: <b><?= $balance ?></b>
            </p>
            <p>
                <?= Yii::t('stock', 'Licences required') ?>: <b><?= $licences_required ?></b>
            </p>
            <?php
            if ($lock_button) {
                ?>
                <p>
                    <?= Yii::t('stock', 'You need {count} more licences', ['count' => $licences_to_buy]) ?>
                    <?php
                    if (Yii::$app->params['manual_mode']) {
                        echo Html::a(Yii::t('app', 'Contact site administrator'), ['/site/contact', 'quantity' => $licences_to_buy], ['class' => 'btn btn-warning']);
                    } else {
                        echo Html::a(\Yii::t('stock', 'Buy Licences'), ['/stock/new', 'id' => 1, 'quantity' => $licences_to_buy], ['class' => 'btn btn-success', 'name' => 'buy-button']);
                    }
                    ?>
                </p>
            <?php } ?>
        </div>
    <?php } ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'newteam-form',
    ]);
    ?>
    <div class="form-group">
        <?= \app\components\SpinnerSubmitButton::widget([
            'caption' => \Yii::t('app', 'Fulfill'),
            'options' => [
                'class' => 'btn btn-' . ($lock_button ? 'default' : 'primary'),
                'disabled' => $lock_button,
            ]
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
