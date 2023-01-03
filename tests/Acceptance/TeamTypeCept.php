<?php

use Tests\Support\AcceptanceTester;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that wheel question works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsAdmin();

$I->clickMainMenu('Admin', 'Tipos de equipos');
$I->wait(1);

$I->click('Duplicar');
$I->acceptPopup();

$I->waitForText('(copy)');
