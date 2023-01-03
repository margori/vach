<?php

use Tests\Support\AcceptanceTester;

/* @var $scenario Codeception\Scenario */

$random = rand(111, 999);
$name = "name$random";
$email = "$name@$name.com";
$phone = "($random)$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that company crud works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);

$I->click('Nueva empresa');
$I->wait(1);

$I->fillField('Company[name]', $name);
$I->fillField('Company[email]', $email);
$I->fillField('Company[phone]', $phone);

$I->click('Guardar');
$I->waitForText('exitosamente');
$I->see($name);

$I->click($name);
$I->wait(1);

$name .= '*';
$I->fillField('Company[name]', $name);

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->see($name);

$I->click('(//a[@title="Eliminar"])[1]');
$I->wait(1);
$I->acceptPopup();
$I->wait(1);

$I->see('exitosamente');

$I->reloadPage();

$I->dontSee($name);

