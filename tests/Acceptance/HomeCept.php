<?php

use Tests\Support\AcceptanceTester;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Nombre usuario');
$I->see('Identificador de rueda');
