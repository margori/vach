<?php

use app\models\Stock;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

?>
<h3><?=Yii::t('user', 'Stocks to transfer')?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $stocks,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'stock_status',
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
            'label' => Yii::t('payment', 'Amount'),
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
    ],
]);
?>
