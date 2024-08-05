<?php

namespace app\modules\admin\controllers;

use app\controllers\LogController;
use app\controllers\SiteController;
use app\models\Company;
use app\models\Payment;
use app\models\Person;
use app\models\search\UserSearch;
use app\models\Stock;
use app\models\Team;
use app\models\TeamCoach;
use app\models\User;
use app\modules\admin\models\UserFusionForm;
use app\modules\admin\models\UserImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * User controller
 */
class UserController extends AdminBaseController {

    public $layout = '//admin';

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
        ];
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@'],],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        if (!isset(Yii::$app->user->identity))
            return $this->redirect(['/site']);
        else if ($action->id != 'my-account' && $action->id != 'find-by-name' && !Yii::$app->user->identity->is_administrator) {
            \Yii::$app->session->addFlash('error', \Yii::t('app', 'Access denied'));
            return $this->redirect(['/site']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $filter = new UserSearch();
        $filter->load(Yii::$app->request->queryParams);
        $users = $filter->adminBrowse();

        return $this->render('index', [
            'filter' => $filter,
            'users' => $users,
        ]);
    }

    public function actionView($id) {
        $user = User::findOne(['id' => $id]);

        return $this->render('view', [
            'user' => $user,
        ]);
    }

    public function actionNew() {
        $user = new User();
        $user->resetPassword = true;

        if ($user->load(Yii::$app->request->post())) {
            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $user->fullname]));

                if ($user->resetPassword) {
                    $this->sendResetPassword($user);
                }

                return $this->redirect(['view', 'id' => $user->id]);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('form', [
            'user' => $user,
            'return' => '/user',
        ]);
    }

    public function actionEdit($id) {
        $user = User::findOne(['id' => $id]);

        if ($user->load(Yii::$app->request->post())) {
            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $user->fullname]));

                if ($user->resetPassword) {
                    $this->sendResetPassword($user);
                }

                return $this->redirect(['view', 'id' => $id]);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('form', [
            'user' => $user,
        ]);
    }

    public function actionDelete($id) {
        $user = User::findOne(['id' => $id]);
        if ($user->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $user->fullname]));
        } else {
            SiteController::FlashErrors($user);
        }

        return $this->redirect(['index']);
    }

    public function actionSetPassword($id) {
        $user = User::findOne(['id' => $id]);
        $user->scenario = User::PASSWORD;

        if ($user->load(Yii::$app->request->post())) {
            $postUser = Yii::$app->request->post('User');
            $password = $postUser['password'];
            $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($password);
            $user->password_hash = $encryptedPassword;

            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('user', 'Password has been successfully saved.'));
                return $this->redirect(['view', 'id' => $id]);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('password', [
            'user' => $user,
        ]);
    }

    public function actionResetPassword($id) {
        $user = User::findOne(['id' => $id]);
        $this->sendResetPassword($user);
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionGeneratePassword($id) {
        $user = User::findOne(['id' => $id]);
        $password = $this->setRandomPassword($user);

        $emailSent = \Yii::$app->mailer->compose('passwordGenerate', ['user' => $user, 'password' => $password])
            ->setFrom(\Yii::$app->params['senderEmail'])
            ->setTo($user->email)
            ->setSubject(\Yii::t('app', 'Password'))
            ->send();

        if (!$emailSent) {
            SiteController::addFlash('error', Yii::t('user', 'Fail to send generated password email.'));
        } else {
            SiteController::addFlash('success', Yii::t('user', 'Generated password email successfully sent.'));

            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('user', 'Password has been successfully saved.'));
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    private function sendResetPassword($user) {
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        if (!$user->save(false, ['password_reset_token'])) {
            SiteController::addFlash('error', Yii::t('app', 'Reset password email sent not to {name}.', ['name' => $user->fullname]));
            return false;
        }

        $resetPasswordEmailSent = \Yii::$app->mailer->compose('passwordResetToken', ['users' => [$user]])
            ->setFrom(\Yii::$app->params['senderEmail'])
            ->setTo($user->email)
            ->setSubject(\Yii::t('app', 'Reset password'))
            ->send();

        if ($resetPasswordEmailSent) {
            SiteController::addFlash('success', Yii::t('app', 'Reset password email sent to {name}.', ['name' => $user->fullname]));
        } else {
            SiteController::addFlash('error', Yii::t('app', 'Reset password email sent not to {name}.', ['name' => $user->fullname]));
        }

        return $resetPasswordEmailSent;
    }

    public function actionFuse() {
        $model = new UserFusionForm();
        if ($model->load(Yii::$app->request->post())) {
            LogController::log(Yii::t('user', 'User {origin} has been fused to {destination}.', [
                'origin' => $model->originUser->username,
                'destination' => $model->destinationUser->username,
            ]));

            if ($model->fuse()) {
                SiteController::addFlash('success', Yii::t('user', 'Users has been successfully fused.'));
                return $this->redirect(['index']);
            }

            SiteController::addFlash('error', Yii::t('user', 'Users has not been fused.'));
        }

        return $this->render('fuse/form', [
            'model' => $model,
        ]);
    }

    public function actionFusionPreview() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPost) {
            $response = [
                'status' => 'error',
                'preview' => '',
            ];
            return $response;
        }

        $originUserId = Yii::$app->request->post('originUserId');

        $persons = Person::find()->where(['coach_id' => $originUserId]);
        $companies = Company::find()->where(['coach_id' => $originUserId]);
        $teams = Team::find()->where(['coach_id' => $originUserId]);
        $teamInvitations = TeamCoach::find()->where(['coach_id' => $originUserId]);
        $stocks = Stock::adminBrowse($originUserId);
        $payments = Payment::adminBrowse($originUserId);

        $previewResult = $this->renderAjax('fuse/_preview', [
            'persons' => $persons,
            'companies' => $companies,
            'teams' => $teams,
            'teamInvitations' => $teamInvitations,
            'stocks' => $stocks,
            'payments' => $payments,
        ]);

        $response = [
            'status' => 'success',
            'preview' => $previewResult,
        ];

        return $response;
    }

    public function actionImport() {
        $model = new UserImport();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->upload()) {
                return $this->redirect(['import-preview',
                    'tempFilename' => $model->tempFilename,
                    'extension' => $model->extension,
                ]);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'File upload failed.'));
        }
        return $this->render('import/index', [
            'model' => $model,
        ]);
    }

    public function actionImportPreview($tempFilename, $extension) {
        $users = $this->readFile($tempFilename, $extension);

        if (Yii::$app->request->isPost) {

            foreach ($users as $user) {
                $this->setRandomPassword($user);
                if ($user->save()) {
                    SiteController::addFlash('success', Yii::t('user', 'User {user} has been saved', ['user' =>$user->username]));
                } else {
                    SiteController::addFlash('warning', Yii::t('user', 'User {user} has not been saved', ['user' =>$user->username]));
                }
            }

            $filePath = Yii::getAlias("@runtime/temp/$tempFilename");
            unlink($filePath);

            return $this->redirect(['index']);
        }

        return $this->render('import/preview', ['users' => $users,]);
    }

    private function readFile($tempFilename, $extension) {
        $reader = IOFactory::createReader(ucfirst($extension));
        $filePath = Yii::getAlias("@runtime/temp/$tempFilename");
        $spreadsheet = $reader->load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);

        $models = [];
        for ($row = 2; $row <= count($sheetData); $row++) {

            if ($sheetData[$row]['A']) {
                $user = new User();

                $user->name = trim($sheetData[$row]['A']);
                $user->surname = trim($sheetData[$row]['B']);
                $user->email = strtolower(trim($sheetData[$row]['C']));
                $user->phone = trim($sheetData[$row]['D']);

                $nameParts = explode(' ', $user->name);
                $surnameParts = explode(' ', $user->surname);

                $user->username = Inflector::transliterate(strtolower($nameParts[0] . '.' . $surnameParts[0]));

                $user->validate();

                $models[] = $user;
            }
        }

        return $models;
    }

    private function setRandomPassword($user) {
        $password = Yii::$app->security->generateRandomString(6);
        $user->setPassword($password);
        return $password;
    }

}
