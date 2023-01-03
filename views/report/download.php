<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;
use app\models\Wheel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('report', 'Report');
?>
<div class="report-technical row">
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/01-frontpage.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/02-VACH.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div class="col-lg-12 text-center">
        <h2>
            <?= Html::label($team->company->name) ?>
            <?= Html::label($team->name) ?>
        </h2>
        <h3>
            <?= Yii::t('report', 'Report') ?> <?= Yii::$app->formatter->asDate('now') ?>
        </h3>
    </div>
    <div class="col-lg-12">
        <h4>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?>
            <br/>
            <?= Yii::t('team', 'Team') ?>: <?= Html::label($team->name) ?>
            <br/>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?>
            <br/>
            <?= Yii::t('team', 'Natural Team') ?>:
            <ul>
                <?php foreach ($team->members as $teamMember) { ?>
                    <li>
                        <?= Html::label($teamMember->member->fullname) ?>
                    </li>
                <?php } ?>
            </ul>
        </h4>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Index') ?>
        </h2>
        <h3>
            <ol>
                <li><?= Yii::t('report', 'Introduction') ?></li>
                <li><?= Yii::t('report', 'Fundaments') ?></li>
                <li><?= Yii::t('report', 'Group and Organizational Analysis') ?></li>
                <li><?= Yii::t('report', 'Individual Analysis') ?></li>
                <li><?= Yii::t('report', 'Summary') ?></li>
                <li><?= Yii::t('report', 'Conclusions - Stage 1') ?></li>
            </ol>
        </h3>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/03-introduction.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <div id="view-introduction">
            <?= $team->report->introduction ?>
        </div>        
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/04-fundaments.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <?= $this->render('_fundaments', []) ?>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/05-team.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <?=
        $this->render('_relations', [
            'team' => $team,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <div style="page-break-after: always"> </div>
        <?=
        $this->render('_effectiveness', [
            'team' => $team,
            'groupRelationsMatrix' => $groupRelationsMatrix,
            'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
            'members' => $members,
        ])
        ?>
        <div style="page-break-after: always"> </div>
        <?=
        $this->render('_performance', [
            'team' => $team,
            'groupPerformanceMatrix' => $groupPerformanceMatrix,
            'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
            'members' => $members,
        ])
        ?>
        <div style="page-break-after: always"> </div>
        <?=
        $this->render('_competences', [
            'team' => $team,
            'groupCompetences' => $groupCompetences,
            'organizationalCompetences' => $organizationalCompetences,
            'members' => $members,
        ])
        ?>
        <div style="page-break-after: always"> </div>
        <?=
        $this->render('_emergents', [
            'team' => $team,
            'groupEmergents' => $groupEmergents,
            'organizationalEmergents' => $organizationalEmergents,
            'members' => $members,
        ])
        ?>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/06-individuals.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <?php
        foreach ($team->report->individualReports as $individualReport) {
            if ($individualReport->teamMember->active) {
                $projectedGroupWheel = Wheel::getProjectedGroupWheel($team->id, $individualReport->person_id);
                $projectedOrganizationalWheel = Wheel::getProjectedOrganizationalWheel($team->id, $individualReport->person_id);
                $reflectedGroupWheel = Wheel::getReflectedGroupWheel($team->id, $individualReport->person_id);
                $reflectedOrganizationalWheel = Wheel::getReflectedOrganizationalWheel($team->id, $individualReport->person_id);
                $groupRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_GROUP);
                $organizationalRelationsMatrix = Wheel::getRelationsMatrix($team->id, Wheel::TYPE_ORGANIZATIONAL);
                $groupCompetences = Wheel::getMemberCompetences($team->id, $individualReport->person_id, Wheel::TYPE_GROUP);
                $organizationalCompetences = Wheel::getMemberCompetences($team->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);
                $groupEmergents = Wheel::getMemberEmergents($team->id, $individualReport->person_id, Wheel::TYPE_GROUP);
                $organizationalEmergents = Wheel::getMemberEmergents($team->id, $individualReport->person_id, Wheel::TYPE_ORGANIZATIONAL);
                $subtitle_number = 97; // letter 'a'
                ?>
                <h1>
                    <?= $individualReport->member->fullname ?>
                </h1>
                <?=
                $this->render('_individual_perception', [
                    'report' => $individualReport,
                    'team' => $team,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                ])
                ?> 
                <div style="page-break-after: always"> </div>
                <?=
                $this->render('_individual_competences', [
                    'report' => $individualReport,
                    'team' => $team,
                    'groupCompetences' => $groupCompetences,
                    'organizationalCompetences' => $organizationalCompetences,
                    'members' => $members,
                ]);
                ?>
                <div style="page-break-after: always"></div>
                <?=
                $this->render('_individual_emergents', [
                    'report' => $individualReport,
                    'team' => $team,
                    'groupEmergents' => $groupEmergents,
                    'organizationalEmergents' => $organizationalEmergents,
                    'members' => $members,
                ]);
                ?>
                <div style="page-break-after: always"> </div>
                <?=
                $this->render('_individual_relations', [
                    'report' => $individualReport,
                    'team' => $team,
                    'groupRelationsMatrix' => $groupRelationsMatrix,
                    'organizationalRelationsMatrix' => $organizationalRelationsMatrix,
                    'members' => $members,
                ]);
                ?>
                <div style="page-break-after: always"> </div>
                <?php
                if ($individualReport->performance != '') {
                    echo $this->render('_individual_performance', [
                        'report' => $individualReport,
                        'team' => $team,
                        'groupPerformanceMatrix' => $groupPerformanceMatrix,
                        'organizationalPerformanceMatrix' => $organizationalPerformanceMatrix,
                        'member' => $individualReport->member,
                    ]);
                }
                ?>
                <div style="page-break-after: always"></div>
                <?php
            }
        }
        ?>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/07-summary.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Summary') ?>
        </h2>
        <div id="view-introduction">
            <?= $team->report->summary ?>
        </div>  
    </div>
    <div style="page-break-after: always"> </div>
    <div class="jumbotron">
        <p>
            <?= Html::img('@web/images/brands/08-plan.png', ['class' => 'image-responsive']) ?>
        </p>
    </div>
    <div style="page-break-after: always"> </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Conclusions - Stage 1') ?>
        </h2>
        <div id="view-introduction">
            <?= $team->report->action_plan ?>
        </div>  
    </div>
</div>
