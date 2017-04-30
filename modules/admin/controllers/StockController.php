<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Account;
use app\models\Payment;
use app\models\Stock;
use app\models\Product;
use app\models\BuyModel;
use app\models\AddModel;
use app\models\RemoveModel;
use app\controllers\SiteController;

class StockController extends \app\controllers\BaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $models = Stock::adminBrowse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Stock::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionNew($product_id = null, $quantity = null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!$product_id) {
            $product_id = Yii::$app->params['default_product_id'];
        }
        if (!$quantity) {
            $quantity = Yii::$app->params['default_quantity'];
        }

        $product = Product::findOne(['id' => $product_id]);

        $model = new BuyModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            // Register new stock
            $stock = new Stock();
            $stock->coach_id = Yii::$app->user->id;
            $stock->creator_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->quantity = $model->quantity;
            $stock->price = $product->price;
            $stock->total = $model->quantity * $product->price;
            $stock->status = Stock::STATUS_INVALID;
            if (!$stock->save()) {
                \app\controllers\SiteController::FlashErrors($stock);
            }

            $payment = new Payment();
            $payment->coach_id = Yii::$app->user->id;
            $payment->creator_id = Yii::$app->user->id;
            $payment->stock_id = $stock->id;
            $payment->concept = $model->quantity . ' ' . $product->name;
            $payment->currency = 'USD';
            $payment->amount = $stock->total;
            $payment->rate = \app\models\Currency::lastValue();
            $payment->status = Payment::STATUS_INIT;
            if (!$payment->save()) {
                \app\controllers\SiteController::FlashErrors($payment);
            }

            $model->description = 'VACH ' . $payment->concept;
            $model->amount = $payment->amount;
            $model->referenceCode = $payment->uuid;

            return $this->render('/payment/redirect', [
                        'model' => $model,
            ]);
        }

        return $this->render('new', [
                    'model' => $model,
        ]);
    }

    public function actionAdd()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $product_id = Yii::$app->params['default_product_id'];
        $quantity = Yii::$app->params['default_quantity'];

        $product = Product::findOne(['id' => $product_id]);

        $model = new AddModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $success = true;
            // Register new stock
            $stock = new Stock();
            $stock->coach_id = $model->coach_id;
            $stock->creator_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->quantity = $model->quantity;
            $stock->price = $model->price;
            $stock->total = $model->quantity * $model->price;
            $stock->status = Stock::STATUS_VALID;
            if (!$stock->save()) {
                $success = false;
                \app\controllers\SiteController::FlashErrors($stock);
            }

            $payment = new Payment();
            $payment->coach_id = $model->coach_id;
            $payment->creator_id = Yii::$app->user->id;
            $payment->stock_id = $stock->id;
            $payment->concept = $model->quantity . ' ' . $product->name;
            $payment->currency = 'USD';
            $payment->amount = $stock->total;
            $payment->rate = \app\models\Currency::lastValue();
            $payment->status = Payment::STATUS_PAID;
            $payment->is_manual = true;
            if (!$payment->save()) {
                $success = false;
                \app\controllers\SiteController::FlashErrors($payment);
            }

            if ($success) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $model->quantity . ' ' . $product->name]));
                return $this->redirect(['/admin/stock']);
            }
        }

        return $this->render('add', [
                    'model' => $model,
        ]);
    }

    public function actionRemove()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $product_id = Yii::$app->params['default_product_id'];
        $quantity = Yii::$app->params['default_quantity'];

        $product = Product::findOne(['id' => $product_id]);

        $model = new RemoveModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $success = true;
            // Register new stock
            $stock = new Stock();
            $stock->coach_id = $model->coach_id;
            $stock->creator_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->quantity = -$model->quantity;
            $stock->price = 0;
            $stock->total = 0;
            $stock->status = Stock::STATUS_VALID;
            if (!$stock->save()) {
                $success = false;
                \app\controllers\SiteController::FlashErrors($stock);
            }

            if ($success) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $model->quantity . ' ' . $product->name]));
                return $this->redirect(['/admin/stock']);
            }
        }

        return $this->render('remove', [
                    'model' => $model,
        ]);
    }

}
