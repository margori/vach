<?php

namespace Tests\Unit\models;

use Tests\Support\UnitTester;
use app\components\ReportHelper;
use app\models\Team;

class ReportCreatorCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function createAnReport(UnitTester $I)
    {
        $team = Team::findOne(1);
        $report = ReportHelper::populate($team);

        $I->assertInstanceOf(\app\models\Report::className(), $report);
    }

    public function createIntroContent(UnitTester $I)
    {
        $team = Team::findOne(1);
        $report = ReportHelper::populate($team);

        $I->assertNotEmpty($report->introduction);
    }

    public function introHasTeamData(UnitTester $I)
    {
        $team = Team::findOne(1);
        $report = ReportHelper::populate($team);

        $I->assertStringContainsString(
            $team->sponsor->name,
            $report->introduction
        );
    }

    public function dontOverwriteIntro(UnitTester $I)
    {
        $text = 'Default';

        $team = Team::findOne(1);
        $report = new \app\models\Report();
        $report->team_id = $team->id;
        $report->save();
        $report->introduction = 'Default';

        $report = ReportHelper::populate($team);

        $I->assertStringContainsString($text, $report->introduction);
    }

    public function createRelationsContent(UnitTester $I)
    {
        $team = Team::findOne(1);
        $report = ReportHelper::populate($team);

        $I->assertNotEmpty($report->relations);
    }
}
