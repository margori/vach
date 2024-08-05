<?php

use app\models\Team;
use app\models\Wheel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('dashboard', 'Dashboard');

$this->params['breadcrumbs'][] = $this->title;

$team = Team::findOne(['id' => $filter->teamId]);
?>
<div class="dashboard">
    <script src="<?=Url::to('@web/js/relations.js')?>"></script>
    <h1><?=Html::encode($this->title)?></h1>
    <?php
echo $this->render('_filter', [
    'filter' => $filter,
    'companies' => $companies,
    'teams' => $teams,
    'team' => $team,
    'members' => $members,
]);

if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL && $team && $team->teamType->isLevelEnabled(Wheel::TYPE_INDIVIDUAL)) {
    echo $this->render('_radar', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => Wheel::TYPE_INDIVIDUAL,
    ]);
}

if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL && $team && $team->teamType->isLevelEnabled(Wheel::TYPE_GROUP)) {
    echo $this->render('_radar', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}

if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0) {
    echo $this->render('_perceptions', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => Wheel::TYPE_GROUP,
    ]);
}

if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL && $team && $team->teamType->isLevelEnabled(Wheel::TYPE_ORGANIZATIONAL)) {
    echo $this->render('_radar', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}

if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0) {
    echo $this->render('_perceptions', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
    ]);
}

if (count($competences) > 0) {
    echo $this->render('_competences', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => $filter->wheelType,
    ]);
}

if (count($relationsMatrix) > 0) {
    echo $this->render('_effectiveness', [
        'data' => $relationsMatrix,
        'members' => $members,
        'type' => $filter->wheelType,
        'memberId' => $filter->memberId,
        'member' => $member,
        'team' => $team,
    ]);
}

if (count($performanceMatrix) > 0) {
    echo $this->render('_performance', [
        'teamId' => $filter->teamId,
        'memberId' => $filter->memberId,
        'wheelType' => $filter->wheelType,
    ]);
}

if (count($relationsMatrix) > 0) {
    if ($filter->memberId > 0) {
        echo $this->render('_relation_graph', [
            'teamId' => $filter->teamId,
            'memberId' => $filter->memberId,
            'wheelType' => $filter->wheelType,
        ]);
    } else {
        echo $this->render('_relation_table', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
        ]);
    }
}

if (count($emergents)) {
    echo $this->render('_emergents', [
        'data' => $relationsMatrix,
        'members' => $members,
        'memberId' => $filter->memberId,
        'emergents' => $emergents,
        'type' => $filter->wheelType,
        'member' => $member,
        'team' => $team,
        'company' => $company,
    ]);
}
echo $this->render('_detailed_emergents', [
    'data' => $relationsMatrix,
    'members' => $members,
    'memberId' => $filter->memberId,
    'emergents' => $emergents,
    'type' => $filter->wheelType,
    'member' => $member,
]);
?>
</div>
<script src="<?=Url::to('@web/js/html2canvas/html2canvas.js')?>"></script>
<script src="<?=Url::to('@web/js/FileSaver.js')?>"></script>
<script>
    function printDiv(div) {
        html2canvas(document.querySelector("#" + div)).then(function (canvas) {
            canvas.toBlob(function (blob) {
                saveAs(blob, "image.png");
            }, "image/png");
        });

    }
</script>
