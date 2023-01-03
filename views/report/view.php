<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Team;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('report', 'Report') . ' ' . $team->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $team->fullname, 'url' => ['/team/view', 'id' => $team->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-6">
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?><br />
        <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?><br />
        <?= Yii::t('team', 'Team') ?>: <?= Html::label($team->name) ?><br />
        <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?><br />
    </div>
    <div class="col-md-6 text-right">
        <?= Html::a(\Yii::t('app', 'Download PPT'), Url::to(['report/presentation', 'id' => $team->id]), ['class' => 'btn btn-success']) ?>
        <?= Html::a(\Yii::t('app', 'Download DOC'), Url::to(['report/word', 'id' => $team->id]), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>
    <h3>
        <?= Yii::t('report', 'Introduction'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/introduction', 'id' => $team->id]), [
            'id' => 'introduction', 'class' => 'btn ' . (empty($team->report->introduction) ? 'btn-success' : 'btn-default')
        ])
        ?>
    </h3>
    <p>
        <?= $team->report->introduction ?>
    </p>
    <h3>
        <?= Yii::t('dashboard', 'Relation Matrix'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/relations', 'id' => $team->id]), [
            'id' => 'relations', 'class' => 'btn ' . (empty($team->report->relations) ? 'btn-success' : 'btn-default')
        ])
        ?>    </h3>
    <p>
        <?= $team->report->relations ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Effectiveness Matrix'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/effectiveness', 'id' => $team->id]), [
            'id' => 'effectiveness', 'class' => 'btn ' . (empty($team->report->effectiveness) ? 'btn-success' : 'btn-default')
        ])
        ?>
    </h3>
    <p>
        <?= $team->report->effectiveness ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Performance Matrix'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/performance', 'id' => $team->id]), [
            'id' => 'performance', 'class' => 'btn ' . (empty($team->report->performance) ? 'btn-success' : 'btn-default')
        ])
        ?>
    </h3>
    <p>
        <?= $team->report->performance ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Competences Matrix'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/competences', 'id' => $team->id]), [
            'id' => 'competences', 'class' => 'btn ' . (empty($team->report->competences) ? 'btn-success' : 'btn-default')
        ])
        ?>
    </h3>
    <p>
        <?= $team->report->competences ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Emergents Matrix'); ?>
        <?=
        Html::a(\Yii::t('app', 'Edit'), Url::to(['report/emergents', 'id' => $team->id]), [
            'id' => 'emergents', 'class' => 'btn ' . (empty($team->report->emergents) ? 'btn-success' : 'btn-default')
        ])
        ?>
    </h3>
    <p>
        <?= $team->report->emergents ?>
    </p>
    <?php
    foreach ($team->report->individualReports as $individualReport) {
        if ($individualReport->teamMember->active) {
            ?>
            <div class="clearfix"></div>
            <h1>
                <?= $individualReport->member->fullname ?>
            </h1>
            <div class="col-md-push-1 col-md-11">
                <h3>
                    <?= Yii::t('report', 'Perception Matrix'); ?>
                    <?=
                    Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-perception',
                                'id' => $individualReport->id]), ['id' => 'perception-' . $individualReport->id,
                        'class' => 'btn ' . (empty($individualReport->perception) ? 'btn-success' : 'btn-default'),
                    ])
                    ?>
                </h3>
                <p>
                    <?= $individualReport->perception ?>
                </p>
                <h3>
                    <?= Yii::t('report', 'Competence Matrix'); ?>
                    <?=
                    Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-competences', 'id' => $individualReport->id]), [
                        'id' => 'individual-competences-' . $individualReport->id,
                        'class' => 'btn ' . (empty($individualReport->competences) ? 'btn-success' : 'btn-default'),
                    ])
                    ?>
                </h3>
                <p>
                    <?= $individualReport->competences ?>
                </p>
                <h3>
                    <?= Yii::t('report', 'Emergent Matrix'); ?>
                    <?=
                    Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-emergents', 'id' => $individualReport->id]), [
                        'id' => 'individual-emergents-' . $individualReport->id,
                        'class' => 'btn ' . (empty($individualReport->emergents) ? 'btn-success' : 'btn-default'),
                    ])
                    ?>
                </h3>
                <p>
                    <?= $individualReport->emergents ?>
                </p>
                <h3>
                    <?= Yii::t('dashboard', 'Relation Matrix'); ?>
                    <?=
                    Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-relations', 'id' => $individualReport->id]), [
                        'id' => 'relations-' . $individualReport->id,
                        'class' => 'btn ' . (empty($individualReport->relations) ? 'btn-success' : 'btn-default'),
                    ])
                    ?>
                </h3>
                <p>
                    <?= $individualReport->relations ?>
                </p>
                <h3>
                    <?= Yii::t('report', 'Performance Matrix'); ?>
                    <?=
                    Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-performance', 'id' => $individualReport->id]), [
                        'id' => 'performance-' . $individualReport->id,
                        'class' => 'btn ' . (empty($individualReport->performance) ? 'btn-success' : 'btn-default'),
                    ])
                    ?>
                </h3>
                <p>
                    <?= $individualReport->performance ?>
                </p>
            </div>
            <?php

        }
    }
    ?>
    <div class="col-md-12">
        <h3>
            <?= Yii::t('report', 'Summary'); ?>
        </h3>
        <p>
            <?= $team->report->summary ?>
        </p>
        <h3>
            <?= Yii::t('report', 'Conclusions - Stage 1'); ?>
            <?=
            Html::a(\Yii::t('app', 'Edit'), Url::to(['report/action-plan', 'id' => $team->id]), [
                'id' => 'action-plan', 'class' => 'btn ' . (empty($team->report->action_plan) ? 'btn-success' : 'btn-default')
            ])
            ?>
        </h3>
        <p>
<?= $team->report->action_plan ?>
        </p>
    </div>
</div>
