<?php

use Tests\Support\AcceptanceTester;

$email = 'coach' . rand(11111, 99999) . '@example.com';
$price = 18;
$add = rand(10, 20);

$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that licence buying works');
$I->markTestSkipped();

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

$I->clickMainMenu('(coach)', 'Mis datos');
$I->wait(1);

$I->fillField('User[email]', $email);

$I->click('Guardar');
$I->wait(1);

$I->clickMainMenu('(coach)', 'Mis licencias');
$I->wait(2);

$I->click('#buy-button');
$I->wait(1);

$I->fillField('BuyModel[quantity]', $add);
$I->wait(1);

$I->click('Siguiente');

$I->wait(1);
$I->click('Comenzar pago');

$I->waitForText('Selecciona el medio de pago', 60);
$I->waitForText((string) Yii::$app->formatter->asDecimal($price * $add), 30);
