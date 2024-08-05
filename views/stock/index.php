<?php

use app\models\Stock;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('stock', 'My Licences');

$this->params['breadcrumbs'][] = $this->title;

$availableDataProvider = new ActiveDataProvider([
  'query' => $availableModels,
  'pagination' => [
    'pageSize' => 20,
  ],
]);
$othersDataProvider = new ActiveDataProvider([
  'query' => $othersModels,
  'pagination' => [
    'pageSize' => 20,
  ],
]);

$columns = [
  [
    'attribute' => 'coach_name',
    'label' => Yii::t('app', 'Coach'),
  ],
  [
    'attribute' => 'status',
    'label' => Yii::t('app', 'Status'),
    'value' => function ($data) {
      return Stock::getStatusList()[$data['stock_status']];
    },
  ],
  [
    'attribute' => 'quantity',
    'label' => Yii::t('stock', 'Quantity'),
  ],
  [
    'attribute' => 'price',
    'label' => Yii::t('stock', 'Price'),
    'value' => function ($data) {
      return 'USD ' . Yii::$app->formatter->asDecimal($data['price'], 2);
    },
  ],
  [
    'attribute' => 'amount',
    'label' => Yii::t('stock', 'Amount'),
    'value' => function ($data) {
      return 'USD ' . Yii::$app->formatter->asDecimal($data['amount'], 2);
    },
  ],
  [
    'attribute' => 'rate',
    'label' => Yii::t('currency', 'Rate'),
    'value' => function ($data) {
      return Yii::$app->formatter->asDecimal($data['rate'], 2);
    },
  ],
  [
    'attribute' => 'localAmount',
    'label' => Yii::t('stock', 'Local Amount'),
    'value' => function ($data) {
      return 'ARS ' . Yii::$app->formatter->asDecimal($data['localAmount'], 2);
    },
  ],
  [
    'attribute' => 'company_name',
    'label' => Yii::t('company', 'Company'),
  ],
  [
    'attribute' => 'team_name',
    'label' => Yii::t('team', 'Team'),
  ],
  [
    'attribute' => 'created_stamp',
    'label' => Yii::t('stock', 'Created'),
    'format' => 'date',
  ],
  [
    'attribute' => 'consumed_stamp',
    'label' => Yii::t('stock', 'Consumed'),
    'format' => 'date',
  ],
  [
    'label' => Yii::t('stock', 'Payed'),
    'value' => function ($data) {
      if ($data['payment_status'] == \app\models\Payment::STATUS_PAID) {
        return Yii::$app->formatter->asDate($data['payment_date']);
      }
      return '';
    },
    'format' => 'html',
  ],
];
?>
<div class="coach-companies">
    <h1><?=Html::encode($this->title)?></h1>
    <h2><?=Yii::t('stock', 'Available licences')?></h2>
    <?=
ExportMenu::widget([
  'dataProvider' => $availableDataProvider,
  'columns' => $columns,
  'exportConfig' => [
    ExportMenu::FORMAT_PDF => false,
  ],
  'fontAwesome' => true,
  'dropdownOptions' => [
    'label' => 'Export All',
    'class' => 'btn btn-default',
  ],
])
?>
    <?=
GridView::widget([
  'dataProvider' => $availableDataProvider,
  'columns' => $columns,
]);
?>
    <p>
        <?=Yii::$app->params['manual_mode'] ?
'' :
Html::a(Yii::t('stock', 'Buy Licences'),
  Url::to(['stock/new']), ['class' => 'btn btn-success', 'id' => 'buy-button']
)?>
    </p>
    <h2><?=Yii::t('stock', 'Not available licences')?></h2>
    <?=
ExportMenu::widget([
  'dataProvider' => $othersDataProvider,
  'columns' => $columns,
  'exportConfig' => [
    ExportMenu::FORMAT_PDF => false,
  ],
  'fontAwesome' => true,
  'dropdownOptions' => [
    'label' => 'Export All',
    'class' => 'btn btn-default',
  ],
])
?>
    <?=
GridView::widget([
  'dataProvider' => $othersDataProvider,
  'columns' => $columns,
]);
?>
</div>
