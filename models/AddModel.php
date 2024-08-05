<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "article".
 *
 * @property double $amount
 *
 */
class AddModel extends Model {

    public $product_id;
    public $quantity;
    public $price;
    public $coach_id;
    public $part_distribution;
    public $payed;
    public $rate;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['product_id', 'quantity', 'price', 'coach_id', 'part_distribution'], 'required'],
            ['quantity', 'number', 'min' => 1, 'max' => 100],
            ['amount', 'number', 'min' => 0, 'max' => 100000],
            ['rate', 'number', 'min' => 0, 'max' => 100000],
            ['payed', 'boolean'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'price' => Yii::t('stock', 'Price in USD $'),
            'quantity' => Yii::t('stock', 'Quantity'),
            'coach_id' => Yii::t('app', 'Coach'),
            'coach' => Yii::t('app', 'Coach'),
            'product_id' => Yii::t('app', 'Product'),
            'product' => Yii::t('app', 'Product'),
            'payed' => Yii::t('stock', 'Payed'),
            'rate' => Yii::t('currency', 'Rate'),
        ];
    }

    public function getCoach() {
        return User::findOne(['id' => $this->coach_id]);
    }

    public function getProduct() {
        return Product::findOne(['id' => $this->product_id]);
    }

}
