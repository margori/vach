<?php

namespace app\models;

use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => \Yii::t('app', 'There is no user with such email.'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('app', 'Email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $users = User::find()->where([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ])->all();

        if (count($users) > 0) {
            foreach ($users as $user) {
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                }
                if (!$user->save(false, ['password_reset_token'])) {
                    return false;
                }
            }

            return \Yii::$app->mailer->compose('passwordResetToken', ['users' => $users])
                ->setFrom(\Yii::$app->params['senderEmail'])
                ->setTo($this->email)
                ->setSubject(\Yii::t('app', 'Password reset'))
                ->send();
        }

        return false;
    }

}
