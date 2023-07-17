<?php

use yii\helpers\Html;
use app\models\Wheel;
use app\components\Utils;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$title = Yii::t('dashboard', 'Consciousness and Responsability Matrix') . ' ' .
  ($type == 1 ? $team->teamType->level_1_name : $team->teamType->level_2_name);

if (!empty($member)) {
  $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
  $title .= ' ' . Yii::t('app', 'of the team');
}

function howISeeMe($data, $observer_id)
{
  foreach ($data as $datum) {
    if ($datum['observer_id'] == $observer_id && $datum['observed_id'] == $observer_id) {
      return $datum['value'];
    }
  }
}

function howTheySeeMe($data, $observer_id)
{
  $sum = 0;
  $count = 0;
  foreach ($data as $datum) {
    if ($datum['observer_id'] != $observer_id && $datum['observed_id'] == $observer_id) {
      $sum += $datum['value'];
      $count++;
    }
  }
  return $count > 0 ? $sum / $count : -1;
}

function howAllTheySee($data)
{
  $sum = 0;
  $count = 0;
  foreach ($data as $datum) {
    if ($datum['observer_id'] != $datum['observed_id']) {
      $sum += $datum['value'];
      $count++;
    }
  }
  return $count > 0 ? $sum / $count : -1;
}

$gaps = [];
$howISeeMe = [];
$howTheySeeMe = [];
foreach ($members as $observer_id => $name) {
  if ($observer_id > 0) {
    $gaps[$observer_id] = howTheySeeMe($data, $observer_id) - howISeeMe($data, $observer_id);
    $howISeeMe[$observer_id] = howISeeMe($data, $observer_id);
    $howTheySeeMe[$observer_id] = howTheySeeMe($data, $observer_id);
  }
}

$standar_deviation = Utils::standard_deviation($gaps);
$productivityDelta = Utils::variance($howTheySeeMe);
$mean_gap = Utils::absolute_mean($gaps);
$token = rand(100000, 999999);
?>
<div class="clearfix"></div>
<h3><?= $title ?></h3>
<div id="div<?= $token ?>" class="row col-md-12">
  <table class="table table-bordered table-hover">
    <tr>
      <td>
        <?= Yii::t('app', 'Description') ?>
      </td>
      <?php
      foreach ($members as $observer_id => $name) {
        if ($observer_id > 0) {
      ?>
          <td>
            <?= $name ?>
          </td>
        <?php } ?>
      <?php } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'How I see me') ?>
      </td>
      <?php foreach ($members as $observer_id => $name) {
        if ($observer_id > 0) {  ?>
          <td>
            <?= round($howISeeMe[$observer_id], 2) ?>
          </td>
      <?php }
      } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'How they see me') ?>
      </td>
      <?php foreach ($members as $observer_id => $name) {
        if ($observer_id > 0) {  ?>
          <td>
            <?= round($howTheySeeMe[$observer_id], 2)  ?>
          </td>
      <?php }
      } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Monofactorial productivity') ?>
      </td>
      <?php foreach ($howTheySeeMe as $value) { ?>
        <td>
          <?= round($value / 4 * 100, 1) . '%' ?>
        </td>
      <?php } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Responsability') ?>
      </td>
      <?php foreach ($howTheySeeMe as $value) { ?>
        <td class="<?= $value < howAllTheySee($data) ? 'warning' : 'success' ?>">
          <?= Utils::productivityText($value, howAllTheySee($data), $productivityDelta) ?>
        </td>
      <?php } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Avg. mon. prod.') ?>
      </td>
      <td>
        <?= round(howAllTheySee($data) / 4 * 100, 1) . '%' ?>
      </td>
      <td colspan="2">
        <?= Yii::t('dashboard', 'Prod. deviation') ?>
      </td>
      <td>
        <?= (round($productivityDelta / 4 * 100, 1)) . '%' ?>
      </td>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Cons. gap') ?>
      </td>
      <?php foreach ($members as $observer_id => $name) {
        if ($observer_id > 0) {  ?>
          <td>
            <?= round(abs($gaps[$observer_id]) / 4 * 100, 1) . '%' ?>
          </td>
      <?php }
      } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Consciousness') ?>
      </td>
      <?php foreach ($members as $observer_id => $name) {
        if ($observer_id > 0) {  ?>
          <td class="<?= abs($gaps[$observer_id]) > ($mean_gap) ? 'warning' : 'success' ?>">
            <?= abs($gaps[$observer_id]) > ($mean_gap) ? Yii::t('app', 'Low') : Yii::t('app', 'High') ?>
          </td>
      <?php }
      } ?>
    </tr>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'Avg. conc. gap') ?>
      </td>
      <td>
        <?= round($mean_gap / 4 * 100, 1) . '%' ?>
      </td>

    </tr>
  </table>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) { ?>
  <div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
  </div>
<?php } ?>
<div class="clearfix"></div>
<?=
$this->render('_rankings', [
  'type' => $type,
  'memberId' => $memberId,
  'member' => $member,
  'team' => $team,
  'members' => $members,
  'howISeeMe' => $howISeeMe,
  'howTheySeeMe' => $howTheySeeMe,
  'gaps' => $gaps,
  'mean_gap' => round($mean_gap / 4 * 100, 1),
  'allTheySee' => round(howAllTheySee($data) / 4 * 100, 1),
]);
?>