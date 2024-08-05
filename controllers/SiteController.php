<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\Wheel;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SiteController extends BaseController {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action) {
        return true;
    }

    public function actionIndex($username = '') {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['/team']);
        }

        $model = new LoginForm();
        $model->username = $username;
        $wheel = new Wheel();

        $nowheel = Yii::$app->request->get('nowheel');
        if (isset($nowheel)) {
            $wheel->addError('token', Yii::t('wheel', 'Wheel not found.'));
        }

        return $this->render('index', [
            'model' => $model,
            'wheel' => $wheel,
        ]);
    }

    public function actionToken() {
        if (!Yii::$app->request->isPost) {
            return $this->goHome();
        }

        $wheel = Yii::$app->request->post('Wheel');
        if (!isset($wheel)) {
            return $this->redirect(['/site', 'nowheel' => 1]);
        }
        $token = $wheel['token'];
        if (!isset($token)) {
            return $this->redirect(['/site', 'nowheel' => 1]);
        }
        return $this->redirect(['wheel/run', 'token' => $token]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['/team']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            LogController::log(Yii::t('app', 'Logged in as {username}.', ['username' => Yii::$app->user->identity->username]));

            $userSession = new \app\models\UserSession();
            $userSession->user_id = Yii::$app->user->id;
            $userSession->token = session_id();
            if (!$userSession->save()) {
                SiteController::FlashErrors($userSession);
            }

            return $this->redirect(['/team']);
        } else {
            $wheel = new Wheel();
            return $this->render('index', [
                'model' => $model,
                'wheel' => $wheel,
            ]);
        }
    }

    public static function checkUserSession() {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(['/site'])->send();
            return false;
        }

        $session = \app\models\UserSession::findOne(['token' => session_id()]);
        if (!$session) {
            $userSession = new \app\models\UserSession();
            $userSession->user_id = Yii::$app->user->id;
            $userSession->token = session_id();
            $userSession->save();
        }

        return true;
    }

    public function actionLogout() {
        Yii::$app->db->createCommand()
            ->delete('user_session', 'token = :token and stamp < :stamp', [
                ':token' => session_id(),
                ':stamp' => (new \DateTime('today -30 days'))->format('Y-m-d H:i:s')])
            ->execute();

        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister() {
        $model = new \app\models\User();
        $model->scenario = \app\models\User::PASSWORD;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->password);
            if ($model->save()) {
                $loginModel = new LoginForm();
                $loginModel->username = $model->username;
                $loginModel->password = $model->password;

                if ($loginModel->login()) {
                    \Yii::$app->session->addFlash('success',
                        \Yii::t('register', 'Sign up successfull. Welcome to ' . Yii::$app->params['app']['name'] . '!'
                        ));
                    return $this->goHome();
                }
            } else {
                \Yii::$app->session->addFlash('error', \Yii::t('register', 'Username already used.'));
            }
        } else {
            SiteController::FlashErrors($model);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        if (empty($token) || !is_string($token)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Password reset token cannot be blank.'));
            return $this->redirect(['index']);
        }

        $model = new ResetPasswordForm($token);

        if (!$model->user) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Wrong password reset token.'));
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'New password was saved.'));
            return $this->redirect(['index', 'username' => $model->username]);
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionCoach() {
        return $this->render('coachIntro', [
        ]);
    }

    public function actionPerson() {
        return $this->render('personIntro', [
        ]);
    }

    public function actionEs() {
        Yii::$app->session->set('language', 'es');
        return $this->goHome();
    }

    public function actionEn() {
        Yii::$app->session->set('language', 'en');
        return $this->goHome();
    }

    public function actionContact() {
        if (!Yii::$app->user->isGuest) {
            $this->layout = 'inner';
        }

        $model = new \app\models\ContactModel();

        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            SiteController::addFlash('success', Yii::t('app', 'Message sent successfully'));
            return $this->goHome();
        }

        if (Yii::$app->request->get('quantity') && empty($model->subject)) {
            $model->subject = Yii::t('stock', 'Requesting licences');
            $model->body = Yii::t('stock', 'Dear administrator, I\'m requesting {q} licences. Thanks.', ['q' => Yii::$app->request->get('quantity')]);
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public static function addFlash($key, $value) {
        \Yii::$app->session->addFlash($key, $value);
        LogController::log($value);
    }

    public static function FlashErrors($record) {
        if (!isset($record)) {
            return;
        }

        foreach ($record->getErrors() as $attribute => $messages) {
            foreach ($messages as $message) {
                self::addFlash('error', \Yii::t('app', 'Problem:') . ' ' . $message);
            }
        }
    }

    public static function Errors($record) {
        if (!isset($record)) {
            return '';
        }

        $result = '';
        foreach ($record->getErrors() as $attribute => $messages) {
            foreach ($messages as $message) {
                $result .= $message . ' ';
            }
        }
        return $result;
    }

    public function actionMigrateUp() {
        // https://github.com/yiisoft/yii2/issues/1764#issuecomment-42436905
        defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
        defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

        $oldApp = \Yii::$app;
        \Yii::$app = new \yii\console\Application([
            'id' => 'Command runner',
            'basePath' => '@app',
            'components' => [
                'db' => $oldApp->db,
            ],
        ]);
        \Yii::$app->runAction('migrate/up', ['migrationPath' => '@app/migrations/', 'interactive' => false]);
        \Yii::$app = $oldApp;
    }

    public function actionBackup() {
        if (\app\components\Backup::createAndSend()) {
            self::addFlash('success', 'Backup sent!');
        }
        return $this->goHome();
    }
}
