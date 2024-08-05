<?php

use app\models\Wheel;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if ($type == Wheel::TYPE_GROUP) {
  $title = Yii::t('dashboard', 'Group Relations Matrix');
} elseif ($type == Wheel::TYPE_ORGANIZATIONAL) {
  $title = Yii::t('dashboard', 'Organizational Relations Matrix');
} else {
  $title = Yii::t('dashboard', 'Individual Relations Matrix');
}

if (!empty($member)) {
  $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
} else {
  $title .= ' ' . Yii::t('app', 'of the team');
}

$token = rand(100000, 999999);

function getCellValue($data, $observerId, $observedId)
{
  $observer_sum = 0;
  $observer_count = 0;

  foreach ($data as $datum) {
    if ($datum['observer_id'] == $observerId && $datum['observed_id'] == $observedId) {
      $observer_sum += $datum['value'];
      $observer_count++;
    }
  }

  return $observer_count > 0 ? $observer_sum / $observer_count : -1;
}

function getCriticity($data, $observerId)
{
  $observer_sum = 0;
  $observer_count = 0;

  foreach ($data as $datum) {
    if ($datum['observer_id'] == $observerId && $datum['observed_id'] != $observerId) {
      $observer_sum += $datum['value'];
      $observer_count++;
    }
  }

  return $observer_count > 0 ? $observer_sum / $observer_count : -1;
}

function getProductivity($data, $observerId, $observedId)
{
  $observer_sum = 0;
  $observer_count = 0;

  foreach ($data as $datum) {
    if ($datum['observer_id'] != $observerId && $datum['observed_id'] == $observerId) {
      $observer_sum += $datum['value'];
      $observer_count++;
    }
  }

  return $observer_count > 0 ? $observer_sum / $observer_count : -1;
}

function getCellHtml($value)
{
  if ($value < 0) {
    return Html::tag('td', '', []);
  }

  if ($value > Yii::$app->params['good_consciousness']) {
    $class = 'success';
  } elseif ($value < Yii::$app->params['minimal_consciousness']) {
    $class = 'danger';
  } else {
    $class = 'warning';
  }

  return Html::tag('td', round($value * 100 / 4, 1) . '%', ['class' => $class]);
}
?>
<div id="div<?= $token ?>" class="row col-md-12">
  <h3><?= Yii::t('dashboard', 'Relation Matrix') ?></h3>
  <table class="table table-bordered table-hover">
    <tr>
      <td>
        <?= Yii::t('wheel', "Observer \\ Observed") ?>
      </td>
      <?php
      foreach ($members as $id => $member) {
        if ($id > 0) {
      ?>
          <td>
            <?= $member ?>
          </td>
      <?php
        }
      } ?>
      <td>
        <?= Yii::t('app', 'Critical') ?>
      </td>
    </tr>
    <?php
    $observed_sum = [];
    foreach ($members as $observerId => $observer) {
      if ($observerId > 0) {
    ?>
        <tr>
          <td>
            <?= $observer ?>
          </td>
          <?php
          foreach ($members as $observedId => $observed) {
            if ($observedId > 0) {
              echo getCellHtml(getCellValue($data, $observerId, $observedId));
            }
          }
          echo getCellHtml(getCriticity($data, $observerId));
          ?>
        </tr>
    <?php
      }
    } ?>
    <tr>
      <td>
        <?= Yii::t('dashboard', 'M. Productivity') ?>
      </td>
      <?php
      foreach ($members as $observerId => $observer) {
        if ($observerId > 0) {
          echo getCellHtml(getProductivity($data, $observerId, $observedId));
        }
      }
      ?>
    </tr>
  </table>
</div>
<?php if (strpos(Yii::$app->request->absoluteUrl, 'download') === false) {
?>
  <div class="col-md-12 text-center">
    <?= Html::button(Yii::t('app', 'Export'), ['class' => 'btn btn-default hidden-print', 'onclick' => "printDiv('div$token')"]) ?>
  </div>
<?php
} ?>
<div class="clearfix"></div>