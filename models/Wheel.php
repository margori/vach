<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @package app\models
 * @property integer id
 * @property integer observer_id
 * @property integer observed_id
 * @property integer type
 * @property string token
 * @property string status
 * @property integer sent_count
 * @property integer team_id
 * @property integer date
 * @property integer created_at
 * @property integer updated_at
 *
 * @property Team team
 * @property Person observer
 * @property Person observed
 * @property WheelAnswer[] answers
 */
class Wheel extends ActiveRecord {

    const TYPE_INDIVIDUAL = 0;
    const TYPE_GROUP = 1;
    const TYPE_ORGANIZATIONAL = 2;

    const STATUS_CREATED = 0;
    const STATUS_SENT = 1;
    const STATUS_RECEIVED = 3;
    const STATUS_IN_PROGRESS = 4;
    const STATUS_DONE = 5;
    const STATUS_ERROR = 255;

    public $dimensionAnswers = [0, 0, 0, 0, 0, 0, 0, 0];

    public function __construct() {
        $this->date = date("Y-m-d");
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'wheel';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['observer_id', 'observed_id', 'date',], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'date' => Yii::t('app', 'Date'),
            'token' => Yii::t('wheel', 'Wheel token'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getAnswerStatus() {
        $count = count($this->answers);
        $questionCount = WheelQuestion::getQuestionCount($this->type);
        return round($count * 100 / $questionCount, 1) . '%';
    }

    public function getObserver() {
        return $this->hasOne(Person::class, ['id' => 'observer_id']);
    }

    public function getObserved() {
        return $this->hasOne(Person::class, ['id' => 'observed_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->observer->coach_id]);
    }

    public function getTeam() {
        return Team::find()
            ->where(['id' => $this->team_id]);
    }

    public function getAnswers() {
        return $this->hasMany(WheelAnswer::class, ['wheel_id' => 'id']);
    }

    public function customSave($answers) {
        if (count($answers) < 80) {
            $this->addError('answers', Yii::t('wheel', 'Some answers left.'));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->validate()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        foreach ($answers as $answer) {
            if (!$answer->validate()) {
                $transaction->rollBack();
                return false;
            }
            $this->link('answers', $answer, ['wheel_id', 'id']);
        }

        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    public static function browse() {
        return Wheel::find()
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('company', 'company.id = team.company_id')
            ->leftJoin('team_coach', '`team_coach`.`team_id` = `wheel`.`team_id`')
            ->where(['team.coach_id' => Yii::$app->user->id])
            ->orderBy('id desc');
    }

    public static function getWheelTypes() {
        return [
            self::TYPE_INDIVIDUAL => Yii::t('wheel', 'Individual Wheel'),
            self::TYPE_GROUP => Yii::t('wheel', 'Group Wheel'),
            self::TYPE_ORGANIZATIONAL => Yii::t('wheel', 'Organizational Wheel'),
        ];
    }

    private static function getProjectedWheel($teamId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("wheel.observer_id = $memberId and wheel.observed_id = $memberId and team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.dimension')
            ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getProjectedIndividualWheel($teamId, $memberId) {
        return self::getProjectedWheel($teamId, $memberId, Wheel::TYPE_INDIVIDUAL);
    }

    public static function getProjectedGroupWheel($teamId, $memberId) {
        return self::getProjectedWheel($teamId, $memberId, Wheel::TYPE_GROUP);
    }

    public static function getProjectedOrganizationalWheel($teamId, $memberId) {
        return self::getProjectedWheel($teamId, $memberId, Wheel::TYPE_ORGANIZATIONAL);
    }

    private static function getReflectedWheel($teamId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("wheel.observer_id <> $memberId and wheel.observed_id = $memberId and team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.dimension')
            ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getReflectedGroupWheel($teamId, $memberId) {
        return self::getReflectedWheel($teamId, $memberId, Wheel::TYPE_GROUP);
    }

    public static function getReflectedOrganizationalWheel($teamId, $memberId) {
        return self::getReflectedWheel($teamId, $memberId, Wheel::TYPE_ORGANIZATIONAL);
    }

    public static function getProdConsMatrix($teamId, $type) {
        $reflectedValues = (new Query)->select('wheel.observed_id, avg(wheel_answer.answer_value) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("team.id = $teamId")
            ->andWhere("wheel.type = $type")
            ->andWhere("wheel.observer_id <> wheel.observed_id")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel.observed_id')
            ->all();

        $projectedValues = (new Query)->select('wheel.observed_id, avg(wheel_answer.answer_value) as value, person.name, person.surname, person.shortname')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('person', 'person.id = wheel.observed_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("team.id = $teamId and wheel.type = $type and wheel.observer_id = wheel.observed_id")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel.observed_id')
            ->all();

        $result = [];
        foreach ($reflectedValues as $reflectedValue)
            foreach ($projectedValues as $projectedValue)
                if ($reflectedValue['observed_id'] == $projectedValue['observed_id']) {
                    $result[] = [
                        'id' => $reflectedValue['observed_id'],
                        'name' => empty($projectedValue['shortname']) ? ($projectedValue['name'] . ' ' . $projectedValue['surname']) : $projectedValue['shortname'],
                        'fullname' => $projectedValue['name'] . ' ' . $projectedValue['surname'],
                        'productivity' => $reflectedValue['value'] / 4 * 100,
                        'steem' => $projectedValue['value'] / 4 * 100,
                        'consciousness' => ($projectedValue['value'] - $reflectedValue['value']) / 4 * 100
                    ];
                }

        return $result;
    }

    public static function getPerceptions($teamId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("wheel.observed_id <> wheel.observer_id and team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.dimension')
            ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getMemberCompetences($teamId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("wheel.observed_id = $memberId and wheel.observer_id <> $memberId and team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.dimension')
            ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getEmergents($teamId, $type) {
        $rawEmergents = (new Query)->select('wheel_question.dimension, wheel_answer.answer_order, question.`text` as question ,'
            . ' avg( case when wheel.observed_id <> wheel.observer_id then wheel_answer.answer_value else null end) as value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('wheel_question', 'wheel_question.order = wheel_answer.answer_order and wheel_question.type = wheel.type and wheel_question.team_type_id = team.team_type_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->innerJoin('question', 'question.id = wheel_question.question_id')
            ->where("team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.answer_order, wheel_question.dimension, question.`text`')
            ->orderBy('avg(wheel_answer.answer_value) desc')
            ->all();

        return $rawEmergents;
    }

    public static function getMemberEmergents($teamId, $memberId, $type) {
        $rawEmergents = (new Query)->select('wheel_question.dimension, wheel_answer.answer_order, question.`text` as question ,'
            . ' avg( case when wheel.observed_id <> wheel.observer_id then wheel_answer.answer_value else null end) as value,'
            . 'avg( case when wheel.observed_id = wheel.observer_id then wheel_answer.answer_value else null end) as mine_value')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('wheel_question', 'wheel_question.order = wheel_answer.answer_order and wheel_question.type = wheel.type and wheel_question.team_type_id = team.team_type_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->innerJoin('question', 'question.id = wheel_question.question_id')
            ->where("team.id = $teamId and wheel.observed_id = $memberId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel_answer.answer_order, wheel_question.dimension, question.`text`')
            ->orderBy('avg(wheel_answer.answer_value) desc')
            ->all();

        return $rawEmergents;
    }

    public static function getRelationsMatrix($teamId, $type) {
        $rawAnswers = (new Query())->select('wheel.observer_id, wheel.observed_id, avg(wheel_answer.answer_value) as value, observer.name as observer_name, observer.surname as observer_surname, observed.name as observed_name, observed.surname as observed_surname,'
            . 'observer.gender as observer_gender, observed.gender as observed_gender')
            ->from('wheel_answer')
            ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
            ->innerJoin('team', 'team.id = wheel.team_id')
            ->innerJoin('person as observer', 'observer.id = wheel.observer_id')
            ->innerJoin('person as observed', 'observed.id = wheel.observed_id')
            ->innerJoin('team_member as m_observed', 'm_observed.person_id = wheel.observed_id and m_observed.team_id = team.id')
            ->innerJoin('team_member as m_observer', 'm_observer.person_id = wheel.observer_id and m_observer.team_id = team.id')
            ->where("team.id = $teamId and wheel.type = $type")
            ->andWhere("m_observed.active = 1")
            ->andWhere("m_observer.active = 1")
            ->groupBy('wheel.observer_id, wheel.observed_id, observer.name, observer.surname, observed.name, observed.surname')
            ->all();

        return $rawAnswers;
    }

    public static function getNewToken() {
        $token_exists = true;
        while ($token_exists) {
            $number = rand(1000000000, 1999999999);
            $string = (string)$number;
            $newToken = $string[1] . $string[2] . $string[3] . '-' .
                $string[4] . $string[5] . $string[6] . '-' .
                $string[7] . $string[8] . $string[9];

            $token_exists = self::doesTokenExist($newToken);
        }
        return $newToken;
    }

    private static function doesTokenExist($token) {
        return (new Query())->select('count(*)')
                ->from('wheel')
                ->where("token = $token")
                ->scalar() > 0;
    }

    /**
     * @return TeamTypeDimension[]
     */
    public function getDimensions() {
        $dimensions = TeamTypeDimension::find()
            ->where([
                'level' => $this->type,
                'team_type_id' => $this->team->team_type_id,
            ])->orderBy('order')
            ->all();
        $result = [];
        foreach ($dimensions as $dimension) {
            $result[$dimension->order] = $dimension;

        }

        return $result;
    }

    /**
     * @return WheelQuestion[]
     */
    public function getQuestions() {
        $questions = WheelQuestion::find()
            ->where([
                'type' => $this->type,
                'team_type_id' => $this->team->team_type_id,
            ])->orderBy('order')
            ->all();

        $result = [];
        foreach ($questions as $question) {
            $result[$question->order] = $question;
        }

        return $result;
    }

    public function getLevelName() {
        return $this->team->teamType->levelName($this->type);
    }

    public function getProgress() {
        $answers = (new Query)->select('count(wheel_answer.id) as count')
            ->from('wheel_answer')
            ->where(['wheel_id' => $this->id])
            ->scalar();

        $questions = WheelQuestion::getQuestionCount($this->type);

        if ($questions == 0) {
            $questions = 1;
        }
        return round($answers / $questions * 100, 1) . '%';
    }

    public function redo() {
        WheelAnswer::deleteAll(['wheel_id' => $this->id]);

        return true;
    }
}
