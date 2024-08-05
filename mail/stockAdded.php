<?php

use yii\widgets\DetailView;
?>
<p>
    Dear administrator, new stock has been added:
</p>
<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'product.name',
            'label' => Yii::t('app', 'Product'),
            'value' => function ($model) {
                return $model->product->name;
            },
        ],
        [
            'attribute' => 'coach.fullname',
            'label' => Yii::t('app', 'Coach'),
            'value' => function ($model) {
                return $model->coach->fullname;
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
                return 'USD ' . \Yii::$app->formatter->asDecimal($data['price'], 2);
            },
        ],
    ],
])
?>
<p>
    Thanks,<br>
    <?=Yii::$app->params['app']['name']?>
</p>