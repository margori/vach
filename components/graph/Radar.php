<?php

namespace app\components\graph;

use Yii;
use app\models\Person;
use app\models\Team;
use app\models\Wheel;
use app\models\WheelQuestion;
use Codeception\Attribute\When;

class Radar
{

    static public function draw($teamId, $memberId, $wheelType)
    {
        $team = Team::findOne(['id' => $teamId]);
        $redWheel = Wheel::getProjectedIndividualWheel($teamId, $memberId);
        switch ($wheelType) {
            case Wheel::TYPE_GROUP:
                $blueWheel = Wheel::getReflectedGroupWheel($teamId, $memberId);
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $blueWheel = Wheel::getReflectedOrganizationalWheel($teamId, $memberId);
                break;
        }

        $dimensions =  $team->teamType->getDimensionNames($wheelType, true);
        if ($wheelType > Wheel::TYPE_INDIVIDUAL) {
            $individual_dimensions = WheelQuestion::getDimensionNames(Wheel::TYPE_INDIVIDUAL, true);
            for ($i = 0; $i < count($dimensions); $i++) {
                $dimensions[$i] = $individual_dimensions[$i] . '/' . $dimensions[$i];
            }
        }

        switch ($wheelType) {
            case Wheel::TYPE_INDIVIDUAL:
                $title = Yii::t('dashboard', 'Individual Wheel');
                break;
            case Wheel::TYPE_GROUP:
                $title = Yii::t('dashboard', 'Individual projection toward the group');
                break;
            case Wheel::TYPE_ORGANIZATIONAL:
                $title = Yii::t('dashboard', 'Individual projection toward the organization');
                break;
        }

        $member = Person::findOne(['id' => $memberId]);

        if (!empty($member)) {
            $title .= ' ' . Yii::t('app', 'of') . ' ' . $member->fullname;
        }

        require Yii::getAlias("@app/components/jpgraph/jpgraph.php");
        require Yii::getAlias("@app/components/jpgraph/jpgraph_radar.php");

        $graph = new \RadarGraph(1400, 800);
        $graph->title->Set($title);
        $graph->title->SetFont(FF_COOL, FS_BOLD, 40);
        $graph->SetColor('white');
        $graph->SetSize(0.65);
        $graph->SetCenter(0.5, 0.55);
        $graph->SetScale('lin', 0, 4);
        $graph->yscale->ticks->Set(4, 1);
        $graph->SetFrame(false);
        $graph->legend->SetFont(FF_COOL, FS_NORMAL, 20);

        $graph->axis->SetFont(FF_COOL, FS_BOLD, 12);
        $graph->axis->title->SetFont(FF_COOL, FS_BOLD, 24);
        $graph->axis->SetWeight(1);

        // Uncomment the following lines to also show grid lines.
        $graph->grid->SetLineStyle('dashed');
        $graph->grid->SetColor('navy@0.5');
        $graph->grid->Show();

        $graph->ShowMinorTickMarks();

        $graph->SetTitles($dimensions);

        $redPlot = new \RadarPlot($redWheel);
        $redPlot->SetLegend(Yii::t('dashboard', 'How I see me'));
        $redPlot->SetColor('red');
        $redPlot->SetFillColor('lightred@0.5');
        $redPlot->SetLineWeight(3);

        $graph->Add($redPlot);

        if (!empty($blueWheel)) {
            $bluePlot = new \RadarPlot($blueWheel);
            $bluePlot->SetLegend(Yii::t('dashboard', 'How they see me'));
            $bluePlot->SetColor('blue');
            $bluePlot->SetFillColor('lightblue@0.5');
            $bluePlot->SetLineWeight(3);

            $graph->Add($bluePlot);
        }

        $graph->Stroke();
        exit();
    }

}
