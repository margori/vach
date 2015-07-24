<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Assessment;
use app\models\DashboardFilter;
use app\models\Wheel;

class AssessmentController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        return $this->redirect(['/team']);
    }

    public function actionView($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('view', [
                    'assessment' => $assessment,
        ]);
    }

    public function actionDelete($id) {
        $assessment = Assessment::findOne(['id' => $id]);
        $teamId = $assessment->team->id;
        if ($assessment->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('assessment', 'Assessment deleted.'));
        } else {
            SiteController::FlashErrors($assessment);
        }
        return $this->redirect(['/team/view', 'id' => $teamId]);
    }

    public function actionSendWheel($id, $memberId, $type) {
        $assessment = Assessment::findOne(['id' => $id]);

        foreach ($assessment->team->members as $teamMember) {
            if ($teamMember->user_id == $memberId) {
                $wheels = [];
                switch ($type) {
                    case Wheel::TYPE_INDIVIDUAL:
                        $wheels = $assessment->individualWheels;
                        break;
                    case Wheel::TYPE_GROUP:
                        $wheels = $assessment->groupWheels;
                        break;
                    default :
                        $wheels = $assessment->organizationalWheels;
                        break;
                }

                foreach ($wheels as $wheel)
                    if ($wheel->observer_id == $memberId && $wheel->answerStatus != '100%') {
                        $this->sendWheel($wheel);
                        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
                    }
            }
        }
    }

    public function actionDetailView($id, $type) {
        $assessment = Assessment::findOne(['id' => $id]);

        return $this->render('detail_view', [
                    'assessment' => $assessment,
                    'type' => $type,
        ]);
    }

    public function actionToggleAutofill($id) {
        $assessment = Assessment::findOne(['id' => $id]);

        $assessment->autofill_answers = !$assessment->autofill_answers;
        $assessment->save();

        return $this->redirect(['/assessment/view', 'id' => $assessment->id]);
    }

    public function actionGoToDashboard($id) {
        $assessment = Assessment::findOne(['id' => $id]);
        $filter = new DashboardFilter();

        $filter->companyId = $assessment->team->company_id;
        $filter->teamId = $assessment->team->id;
        $filter->assessmentId = $id;
        $filter->wheelType = Wheel::TYPE_GROUP;

        Yii::$app->session->set('DashboardFilter', $filter);
        $this->redirect(['/dashboard']);
    }

    private static function newToken() {
        $token_exists = true;
        while ($token_exists) {
            $number = rand(1000000000, 1999999999);
            $string = (string) $number;
            $newToken = $string[1] . $string[2] . $string[3] . '-' .
                    $string[4] . $string[5] . $string[6] . '-' .
                    $string[7] . $string[8] . $string[9];

            $token_exists = Wheel::doesTokenExist($newToken);
        }
        return $newToken;
    }

    private function sendWheel($wheel) {
        $type_text = Wheel::getWheelTypes()[$wheel->type];
        Yii::$app->mailer->compose('wheel', [
                    'wheel' => $wheel,
                ])
                ->setSubject(Yii::t('assessment', 'CPC: access to {wheel} of assessment {assessment}', [
                            'wheel' => $type_text,
                            'assessment' => $wheel->assessment->name,
                ]))
                ->setFrom($wheel->coach->email)
                ->setTo($wheel->observer->email)
                ->send();

        \Yii::$app->session->addFlash('success', \Yii::t('assessment', 'Wheel sent to {user}', ['user' => $wheel->observer->fullname]));
    }

}

