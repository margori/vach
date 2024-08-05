<?php

use app\components\Icons;
use yii\bootstrap\Nav;
use yii\helpers\Url;

$isAdministrator = false;
if (Yii::$app->user->identity) {
  $isAdministrator = Yii::$app->user->identity->is_administrator;
}

$items[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']];

$coachMenu[] = ['label' => Yii::t('company', 'Companies'), 'url' => ['/company']];
$coachMenu[] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$coachMenu[] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$coachMenu[] = ['label' => Yii::t('team', 'Wheels'), 'url' => ['/wheel']];
$coachMenu[] = ['label' => Yii::t('dashboard', 'Dashboard'), 'url' => ['/dashboard']];
$items[] = ['label' => Yii::t('app', 'Clients'), 'items' => $coachMenu];

$assisstanceMenu[] = ['label' => Yii::t('help', 'User Guide'), 'url' => Url::to('@web/docs/user.guide.es.pdf')];
if ($isAdministrator) {
  $assisstanceMenu[] = ['label' => Yii::t('help', 'Admin Guide'), 'url' => Url::to('@web/docs/user.guide.es.pdf')];
}
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty individual wheel form'), 'url' => Url::to('@web/docs/individual.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty group wheel form'), 'url' => Url::to('@web/docs/group.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('help', 'Empty organizational wheel form'), 'url' => Url::to('@web/docs/organizational.wheel.form.es.pdf')];
$assisstanceMenu[] = ['label' => Yii::t('log', 'Event Log'), 'url' => ['/log']];
$items[] = ['label' => Yii::t('help', 'Help'), 'items' => $assisstanceMenu];

if ($isAdministrator) {
  $admininistratorMenu[] = ['label' => Yii::t('user', 'Users'), 'url' => ['/admin/user']];
  $admininistratorMenu[] = ['label' => Yii::t('user', 'Fuse Users'), 'url' => ['/admin/user/fuse']];
  $admininistratorMenu[] = '<li class="divider"></li>';
  $admininistratorMenu[] = ['label' => Yii::t('stock', 'Licences'), 'url' => ['/admin/stock']];
  $admininistratorMenu[] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/admin/payment']];
  $admininistratorMenu[] = ['label' => Yii::t('payment', 'Liquidations'), 'url' => ['/admin/liquidation']];
//    $admininistratorMenu[] = '<li class="divider"></li>';
//    $admininistratorMenu[] = ['label' => Yii::t('company', 'Companies'), 'url' => ['/admin/company']];
//    $admininistratorMenu[] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/admin/person']];
//    $admininistratorMenu[] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/admin/team']];
  $admininistratorMenu[] = '<li class="divider"></li>';
  $admininistratorMenu[] = ['label' => Yii::t('team', 'Team Types'), 'url' => ['/admin/team-type']];
  $admininistratorMenu[] = ['label' => Yii::t('stock', 'Licence Types'), 'url' => ['/admin/product']];
  $admininistratorMenu[] = '<li class="divider"></li>';
  $admininistratorMenu[] = ['label' => Yii::t('feedback', 'Feedbacks'), 'url' => ['/admin/feedback']];
  $admininistratorMenu[] = ['label' => Yii::t('app', 'Backup'), 'url' => ['/site/backup']];
  $admininistratorMenu[] = '<li class="divider"></li>';
  $admininistratorMenu[] = ['label' => Yii::t('app', 'Test email'), 'url' => ['/admin/test/email']];
  $items[] = ['label' => Yii::t('app', 'Admin'), 'items' => $admininistratorMenu];
}

$userMenu[] = ['label' => Yii::t('user', 'My Data'), 'url' => ['/user/my-account']];
$userMenu[] = ['label' => Yii::t('user', 'My Password'), 'url' => ['/user/my-password']];
if (Yii::$app->params['monetize']) {
  $userMenu[] = ['label' => Yii::t('stock', 'My Licences'), 'url' => ['/stock']];
  if (!Yii::$app->params['manual_mode']) {
    $userMenu[] = ['label' => Yii::t('stock', 'Buy Licences'), 'url' => ['/stock/new']];
  }
  $userMenu[] = ['label' => Yii::t('payment', 'My Payments'), 'url' => ['/payment']];
}
$userMenu[] = ['label' => Yii::t('app', 'Logout'),
  'url' => ['/site/logout'],
  'linkOptions' => ['data-method' => 'post']];
$items[] = [
  'label' => Icons::USER . ' (' . (Yii::$app->user->identity ? Yii::$app->user->identity->username : '') . ')',
  'items' => $userMenu,
];

echo Nav::widget([
  'id' => 'navbar',
  'options' => ['class' => 'navbar-nav navbar-right'],
  'items' => $items,
  'encodeLabels' => false,
  'activateParents' => true,
]);
