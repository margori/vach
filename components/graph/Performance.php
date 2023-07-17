<?php

namespace app\components\graph;

use app\components\Utils;
use app\models\Person;
use app\models\Team;
use app\models\Wheel;
use Yii;
use yii\helpers\ArrayHelper;

class Performance
{

  static public function draw($teamId, $memberId, $wheelType)
  {
    $team = Team::findOne($teamId);
    $title = Yii::t('dashboard', 'Performance Matrix') . ' ' .
      ($wheelType == 1 ? $team->teamType->level_1_name : $team->teamType->level_2_name);
    $performanceMatrix = Wheel::getProdConsMatrix($teamId, $wheelType);
    Yii::debug($performanceMatrix);

    $member = Person::findOne(['id' => $memberId]);

    if (!empty($member)) {
      $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
    } else {
      $title .= ' ' . Yii::t('app', 'of the team');
    }

    $width = 1800;
    $height = 800;
    $topMargin = 60;
    $bottomMargin = 50;
    $horizontalMargin = 160;
    $matrixData = [];
    $minx = $horizontalMargin;
    $minProductivity = 10000;
    $maxx = $width - $horizontalMargin;
    $maxProductivity = -10000;
    $maxConsciousness = -100000;
    $sumConsciousness = 0;
    $sumProductivity = 0;
    foreach ($performanceMatrix as $data) {
      if ($data['productivity'] < $minProductivity) {
        $minProductivity = $data['productivity'];
      }
      if ($data['productivity'] > $maxProductivity) {
        $maxProductivity = $data['productivity'];
      }
      if (abs($data['consciousness']) > $maxConsciousness) {
        $maxConsciousness = abs($data['consciousness']);
      }
      $sumConsciousness += abs($data['consciousness']);
      $sumProductivity += $data['productivity'];
    }

    require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
    require Yii::getAlias("@app/components/jpgraph/jpgraph_canvas.php");
    require Yii::getAlias("@app/components/jpgraph/jpgraph_canvtools.php");

    // Setup a basic canvas we can work
    $g = new \CanvasGraph($width, $height);
    $g->SetFrame(false);

    // We need to stroke the plotarea and margin before we add the
    // text since we otherwise would overwrite the text.
    $g->InitFrame();

    if (count($performanceMatrix) == 0) {
      $g->Stroke();
      exit();
    }

    $avgConsciousness = $sumConsciousness / count($performanceMatrix);
    $avgProductivity = $sumProductivity / count($performanceMatrix);
    $deltax = $maxx - $minx;
    $deltaProductivity = $maxProductivity - $minProductivity;

    $avgDeltaProductivity = Utils::variance(ArrayHelper::getColumn($performanceMatrix, 'productivity'));

    $maxy = (floor(($maxConsciousness + 1) / 10) + 1.1) * 10;

    $m = ($topMargin + $bottomMargin - $height) / 2 / $maxy;
    foreach ($performanceMatrix as $data) {
      $posx = floor(($data['productivity'] - $minProductivity) / $deltaProductivity * $deltax + $minx);
      $posy = floor(($data['consciousness'] - $maxy) * $m) + $topMargin;
      $matrixData[] = [
        'name' => $data['name'],
        'x' => $posx,
        'y' => $posy,
        'id' => $data['id'],
        'consciousness' => $data['consciousness'],
        'productivity' => $data['productivity'],
      ];
    }

    $goodConsciousnessY1 = floor(($avgConsciousness - $maxy) * $m) + $topMargin;
    $goodConsciousnessY2 = floor((-$avgConsciousness - $maxy) * $m) + $topMargin;
    $goodConsciousnessYAxe = floor(($height - $topMargin - $bottomMargin) / 2) + $topMargin;
    $goodProductivityX1 = ($avgProductivity - $avgDeltaProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;
    $goodProductivityX2 = ($avgProductivity + $avgDeltaProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;

    // Lets draw

    //high conciouness zone
    $g->img->SetColor('#d9edf7');
    $g->img->FilledRectangle(-1, $goodConsciousnessY1, $width, $goodConsciousnessY2);

    $g->img->SetColor('#5a9bbc');
    $g->img->Rectangle(-1, $goodConsciousnessY1, $width, $goodConsciousnessY2);

    // texts

    $t = new \Text($title, $width / 2, 5);
    $t->SetFont(FF_COOL, FS_BOLD, 28);
    $t->Align('center', 'top');
    $t->SetColor("black");
    $t->Stroke($g->img);

    $t = new \Text('BP/BC+', 5, 5 + $topMargin);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('left', 'top');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('BP/AC', 5, $goodConsciousnessYAxe - 5);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('left', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('BP/BC-', 5, $height - $bottomMargin - 5);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('left', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('AP/BC+', $width - 5, 5 + $topMargin);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('right', 'top');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('AP/AC', $width - 5, $goodConsciousnessYAxe - 5);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('right', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('AP/BC-', $width - 5, $height - $bottomMargin - 5);
    $t->SetFont(FF_COOL, FS_NORMAL, 24);
    $t->Align('right', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    //axes
    $posx = ($avgProductivity - $minProductivity) / $deltaProductivity * $deltax + $minx;
    $g->img->SetColor('#5a9bbc');
    $g->img->SetLineWeight(2);
    $g->img->Line(0, $goodConsciousnessYAxe, $width, $goodConsciousnessYAxe);
    $g->img->Line($posx, $topMargin, $posx, $height - $bottomMargin);
    $g->img->Line($minx, $topMargin, $minx, $height - $bottomMargin);
    $g->img->Line($maxx, $topMargin, $maxx, $height - $bottomMargin);

    //high productivity zone
    $g->img->SetLineWeight(1);
    $g->img->Line($goodProductivityX1, $topMargin, $goodProductivityX1, $height - $bottomMargin);
    $g->img->Line($goodProductivityX2, $topMargin, $goodProductivityX2, $height - $bottomMargin);

    //axes values
    $t = new \Text('Min: ' . round($minProductivity * 10) / 10 . '%', $minx, $height - 6);
    $t->SetFont(FF_COOL, FS_NORMAL, 20);
    $t->Align('center', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('Max: ' . round($maxProductivity * 10) / 10 . '%', $maxx, $height - 6);
    $t->SetFont(FF_COOL, FS_NORMAL, 20);
    $t->Align('center', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    $t = new \Text('Prom: ' . round($avgProductivity * 10) / 10 . '%', $posx, $height - 6);
    $t->SetFont(FF_COOL, FS_NORMAL, 20);
    $t->Align('center', 'bottom');
    $t->SetColor("red");
    $t->Stroke($g->img);

    foreach ($matrixData as $data) {
      $highConsciousness = abs($data['consciousness']) < $avgConsciousness;

      $g->img->SetColor('#496987');
      $g->img->FilledCircle($data['x'], $data['y'], ($data['id'] == $memberId ? 30 : 22));

      if ($highConsciousness) {
        $g->img->SetColor('#5cb85c');
      } else {
        $g->img->SetColor('#f0ad4e');
      }

      $g->img->FilledCircle($data['x'], $data['y'], 20);

      $t = new \Text($data['name'], $data['x'], $data['y'] + 32);
      if ($data['id'] == $memberId) {
        $t->SetFont(FF_COOL, FS_BOLD, 20);
      } else {
        $t->SetFont(FF_COOL, FS_NORMAL, 20);
      }
      $t->Align('center', 'top');
      $t->SetColor("#496987");
      $t->Stroke($g->img);
    }

    // Stroke the graph
    $g->Stroke();
    exit();
  }
}
