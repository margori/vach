<?php

namespace app\modules\admin\controllers;

use app\controllers\SiteController;
use app\models\AddModel;
use app\models\BuyModel;
use app\models\Currency;
use app\models\Product;
use app\models\RemoveModel;
use app\models\Stock;
use app\models\User;
use Yii;

class StockController extends AdminBaseController {

    public $layout = '//admin';

    public function actionIndex() {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $availableModels = Stock::adminBrowseAvailable();
        $othersModels = Stock::adminBrowseOthers();

        return $this->render('index', [
            'availableModels' => $availableModels,
            'othersModels' => $othersModels,
        ]);
    }

    public function actionView($id) {
        $model = Stock::findOne(['id' => $id]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionNew($product_id = null, $quantity = null) {
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
            'part_distribution' => 0,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            Stock::saveBuyModel($model);

            if (Yii::$app->params['payu']) {
                return $this->render('/payment/payu/redirect', [
                    'model' => $model,
                ]);
            } else if (Yii::$app->params['paypal']) {
                return $this->render('/payment/paypal/handle', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('new', [
            'model' => $model,
        ]);
    }

    public function actionAdd() {
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
            'payed' => false,
            'rate' => Currency::getLastValue(),
        ]);

        if ($model->load(Yii::$app->request->post())) {

            $success = Stock::applyAddModel($model);

            if ($success) {
                Yii::$app->mailer->compose('stockAdded', [
                    'model' => $model,
                ])
                    ->setSubject(Yii::t('team', 'CPC: stock added'))
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo(User::getAdminEmails())
                    ->send();

                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $model->quantity . ' ' . $product->name]));
                return $this->redirect(['/admin/stock']);
            }
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

    public function actionRemove() {
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
            if ($model->quantity <= $model->coach->getCancelableStock()) {
                $canceledCount = Stock::cancel($model->coach_id, $model->quantity, $model->product_id);
                if ($canceledCount == $model->quantity) {
                    SiteController::addFlash('success', Yii::t('app', '{name} has been successfully canceled.', ['name' => $model->quantity . ' ' . $product->name]));
                } else {
                    SiteController::addFlash('error', Yii::t('app', '{name} has been canceled.', ['name' => $canceledCount . ' ' . $product->name]));
                }
                return $this->redirect(['/admin/stock']);
            } else {
                SiteController::addFlash('error', Yii::t('stock', 'Stock quantity not available'));
            }
        }

        return $this->render('remove', [
            'model' => $model,
        ]);
    }

}
