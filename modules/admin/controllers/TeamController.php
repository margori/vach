<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Team;
use app\models\TeamMember;
use app\models\Company;
use app\models\Person;

class TeamController extends AdminBaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $teams = Team::browse();

        return $this->render('index', [
                    'teams' => $teams,
        ]);
    }

    public function actionView($id)
    {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        if (Yii::$app->request->isPost) {
            $new_member_id = Yii::$app->request->post('new_member');

            $teamMember = new TeamMember();
            $teamMember->person_id = $new_member_id;
            $teamMember->team_id = $team->id;
            if ($teamMember->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully added to {group}.', ['name' => $teamMember->member->fullname, 'group' => $team->fullname]));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else
                SiteController::FlashErrors($teamMember);
        }

        $persons = [];
        if (!$team->blocked) {
            $persons = $this->getPersons();
        }

        return $this->render('view', [
                    'team' => $team,
                    'persons' => $persons,
        ]);
    }

    public function actionNew()
    {
        $team = new Team();

        if ($team->load(Yii::$app->request->post()) && $team->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $team->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    public function actionEdit($id)
    {
        $team = Team::findOne(['id' => $id]);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        Yii::$app->session->set('team_id', $id);

        if ($team->load(Yii::$app->request->post()) && $team->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $team->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->render('form', [
                    'team' => $team,
                    'companies' => $this->getCompanies(),
                    'persons' => $this->getPersons(),
        ]);
    }

    private function save($team)
    {
        
    }

    public function actionDelete($id)
    {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        if ($team->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $team->name]));
        } else {
            SiteController::FlashErrors($team);
        }
        return $this->redirect(['/team']);
    }

    public function actionFullfilled($id)
    {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        $team->blocked = true;

        if ($team->save()) {
            return $this->redirect(['team/new', 'teamId' => $team->id]);
        } else {
            SiteController::FlashErrors($team);
        }

        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionNewMember($id)
    {
        $team = Team::findOne($id);

        if (!isset($team)) {
            return $this->redirect(['/team']);
        }

        $member = new Person();

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            $teamMember = new TeamMember();
            $teamMember->person_id = $member->id;
            $teamMember->team_id = $team->id;
            $teamMember->save();
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $member->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        } else
            SiteController::FlashErrors($member);

        return $this->render('member-form', [
                    'team' => $team,
                    'member' => $member,
        ]);
    }

    public function actionEditMember($id)
    {
        $teamMember = TeamMember::findOne($id);

        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $member->fullname]));
            return $this->redirect(['/team/view', 'id' => $team->id]);
        }
        return $this->render('member-form', [
                    'team' => $team,
                    'member' => $member,
        ]);
    }

    public function actionDeleteMember($id)
    {
        $teamMember = TeamMember::findOne($id);
        $team = $teamMember->team;
        $member = $teamMember->member;

        if ($teamMember->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully removed from {group}.', ['name' => $member->fullname, 'group' => $team->fullname]));
        } else {
            SiteController::FlashErrors($teamMember);
        }
        return $this->redirect(['/team/view', 'id' => $team->id]);
    }

    public function actionDeleteTeam($id)
    {
        $team = Team::findOne($id);
        $team = $team;

        if (Yii::$app->request->post('delete')) {
            if ($team->delete()) {

                SiteController::addFlash('success', Yii::t('team', 'Team has been successfully deleted.'));
                return $this->redirect(['/team/view', 'id' => $team->id]);
            } else {
                SiteController::FlashErrors($team);
            }
        }

        return $this->render('delete_team', [
                    'team' => $team,
                    'team' => $team,
        ]);
    }

    public function actionActivateMember()
    {
        $id = Yii::$app->request->get("id");
        $isActive = Yii::$app->request->get("isActive");

        $teamMember = TeamMember::findOne(['id' => $id]);

        if ($teamMember) {
            $teamMember->active = $isActive;
            $teamMember->save(false);
            return 'ok';
        }

        return 'error';
    }

    private function getCompanies()
    {
        return $companies = ArrayHelper::map(Company::browse()->asArray()->all(), 'id', 'name');
    }

    private function getPersons()
    {
        $persons = [];
        foreach (Person::browse()->all() as $person)
            $persons[$person->id] = $person->fullname;
        return $persons;
    }

}
