<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Person;

class PersonController extends AdminBaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $persons = Person::browse();
        return $this->render('index', [
                    'persons' => $persons,
        ]);
    }

    public function actionView($id)
    {
        $person = Person::findOne(['id' => $id]);
        Yii::$app->session->set('person_id', $id);
        return $this->render('view', [
                    'person' => $person,
        ]);
    }

    public function actionNew()
    {
        $person = new Person();

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionEdit($id)
    {
        $person = Person::findOne(['id' => $id]);

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionDelete($id)
    {
        $person = Person::findOne(['id' => $id]);
        if ($person->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }
    }

}
