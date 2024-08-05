<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Payment
 * @package app\models
 * @property integer id
 * @property integer coach_id
 * @property integer creator_id
 * @property string concept
 * @property string currency
 * @property double amount
 * @property double rate
 * @property string commision_currency
 * @property double commision
 * @property double part_distribution
 * @property string status
 * @property boolean is_manual
 *
 * @property User $coach
 */
class Payment extends ActiveRecord {

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ERROR = 'error';

    public $external_data;

    public function init() {
        parent::init();
        $this->uuid = uniqid('', true);
        $this->stamp = date('Y-m-d H:i:s');
        $this->creator_id = Yii::$app->user->id;
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['coach_id', 'uuid', 'concept', 'amount', 'currency', 'status', 'stamp'], 'required'],
            [['external_id', 'rate', 'commision', 'commision_currency'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'uuid' => Yii::t('app', 'Unique ID'),
            'concept' => Yii::t('app', 'Concept'),
            'amount' => Yii::t('app', 'Trans. Amount'),
            'currency' => Yii::t('payment', 'Currency'),
            'rate' => Yii::t('payment', 'Rate'),
            'commision' => Yii::t('payment', 'Commision'),
            'commision_currency' => Yii::t('payment', 'Commision currency'),
            'status' => Yii::t('app', 'Status'),
            'is_manual' => Yii::t('payment', 'Is manual'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'log' => Yii::t('app', 'Log'),
            'transactions' => Yii::t('payment', 'Transactions'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
        ];
    }

    public function beforeValidate() {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        $log = new PaymentLog();
        $log->payment_id = $this->id;
        $log->status = $this->status;
        $log->stamp = date('Y-m-d H:i:s');
        if (!Yii::$app->user->isGuest) {
            $log->creator_id = Yii::$app->user->id;
        }

        if (!$log->save()) {
            \app\controllers\SiteController::FlashErrors($log);
        }
    }

    public static function browse() {
        return Payment::find()
            ->where(['coach_id' => Yii::$app->user->id])
            ->andwhere(['>', 'amount', 0])
            ->orderBy('id desc');
    }

    public static function adminBrowse($coachId = null) {
        return Payment::find()
            ->where(['>', 'amount', 0])
            ->andWhere(['>', 'rate', 0])
            ->andFilterWhere(['coach_id' => $coachId])
            ->orderBy('id desc');
    }

    public static function adminBrowsePendings() {
        return Payment::find()
            ->where(['status' => 'pending'])
            ->andWhere(['>', 'amount', 0])
            ->andWhere(['>', 'rate', 0])
            ->orderBy('id desc');
    }

    public static function getStatusList() {
        $list = [
            self::STATUS_PENDING => Yii::t('app', self::STATUS_PENDING),
            self::STATUS_PAID => Yii::t('app', self::STATUS_PAID),
            self::STATUS_REJECTED => Yii::t('app', self::STATUS_REJECTED),
            self::STATUS_ERROR => Yii::t('app', self::STATUS_ERROR),
        ];

        return $list;
    }

    public function getName() {
        return Yii::t('payment', 'Payment') . ' ' . $this->id;
    }

    public function getLocalAmount() {
        return $this->amount * $this->rate;
    }

    public function getNetAmount() {
        return $this->amount * $this->rate - $this->commision;
    }

    public function getStatusName() {
        return self::getStatusList()[$this->status];
    }

    public function getCoach() {
        return $this->hasOne(User::class, ['id' => 'coach_id']);
    }

    public function getCreator() {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    public function getLogs() {
        return $this->hasMany(PaymentLog::class, ['payment_id' => 'id']);
    }

    public function getStocks() {
        return $this->hasMany(Stock::class, ['payment_id' => 'id']);
    }

    public function getPart1Amount() {
        return $this->netAmount * $this->part_distribution / 100;
    }

    public function getPart2Amount() {
        return $this->netAmount * (100 - $this->part_distribution) / 100;
    }

    public function getTransactions() {
        return $this->hasMany(Transaction::class, ['payment_id' => 'id']);
    }

    public function newTransaction() {
        $transaction = new Transaction();

        $transaction->payment_id = $this->id;
        $transaction->amount = $this->amount;
        $transaction->currency = $this->currency;
        $transaction->rate = 0;
        $transaction->commision = 0;
        $transaction->commision_currency = "";
        $transaction->save();

        return $transaction;
    }

}
