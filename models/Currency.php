<?php

namespace app\models;

use app\controllers\LogController;
use Yii;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord {

    public function __construct() {
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%currency}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['from_currency', 'to_currency', 'rate', 'stamp'], 'required'],
        ];
    }

    public function behaviors() {
        return [
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'from_currenct' => Yii::t('currency', 'From Currency'),
            'to_currency' => Yii::t('currency', 'To Currency'),
            'rate' => Yii::t('currency', 'Rate'),
        ];
    }

    public static function browse() {
        return Currency::find()->orderBy('id desc');
    }

    public static function getLastValue() {
        $lastRate = Currency::find()->orderBy('stamp desc')->one();

        if (!$lastRate) {
            return 0;
        }

        return $lastRate->rate;
    }

    public static function saveLastRate($newRate) {
        $lastRate = self::getLastValue();

        if ($newRate == $lastRate) {
            return;
        }

        $newCurrency = new Currency();
        $newCurrency->from_currency = 'ARS';
        $newCurrency->to_currency = 'USD';
        $newCurrency->rate = $newRate;

        if (!$newCurrency->save()) {
            $errors = $newCurrency->getErrors();
            LogController::log('Currency not saved: ' . print_r($errors));
        }

        LogController::log('Currency saved');
    }
}
