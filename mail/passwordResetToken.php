<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLinks = [];
foreach ($users as $user) {
    $resetLinks[$user->username] = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
}
?>
    <div class="password-reset">
        <p><?=\Yii::t('app', 'Welcome to ') . Yii::$app->params['app']['name']?>,</p>

        <?php if (count($resetLinks) == 1) {?>
            <p>
                <?=Yii::t('user', 'Your username is')?> <strong><?=$users[0]->username?></strong>.
            </p>
            <p>
                <?=\Yii::t('app', 'Follow the link below to set your password:')?>
            </p>
            <p>
                <?=Html::a(Html::encode($resetLinks[$users[0]->username]), $resetLinks[$users[0]->username])?>
            </p>
        <?php } else {?>
            <p><?=\Yii::t('app', 'Follow the links below to set your passwords:')?></p>

            <ul>
                <?php foreach ($resetLinks as $username => $link) {?>
                    <li>
                        <?=Yii::t('user', 'Username')?> <strong><?=$username?></strong>
                        <br/>
                        <?=Html::a(Html::encode($link), $link)?>
                    </li>
                <?php }?>
            </ul>
        <?php }?>
    </div>
<?=$this->render('_footer')?>
