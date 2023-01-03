<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
$this->title = Yii::t('team', 'Team Types');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-team-types">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $teamTypes,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a(
                        $data->name,
                        Url::to(['team-type/view', 'id' => $data['id']])
                    );
                },
            ],
            [
                'attribute' => 'name',
                'label' => '',
                'format' => 'raw',
                'options' => ['width' => '110px'],
                'value' => function ($data) {
                    return Html::a(
                        Yii::t('app', 'Duplicate'),
                        Url::to(['team-type/duplicate', 'id' => $data['id']]),
                        [
                            'class' => 'btn btn-warning',
                            'data-confirm' => Yii::t(
                                'team',
                                'Are you sure you want to duplicate this team type?'
                            ),
                        ]
                    );
                },
            ],
            [
                'class' => 'app\components\grid\ActionColumn',
                'template' => '{duplicate} {update} {delete}',
                'options' => ['width' => '110px'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch ($action) {
                        case 'update':
                            return Url::to([
                                'team-type/edit',
                                'id' => $model['id'],
                            ]);
                        case 'delete':
                            return Url::to([
                                'team-type/delete',
                                'id' => $model['id'],
                                'delete' => '1',
                            ]);
                    }
                },
            ],
        ],
    ]);
    ?>
</div>
