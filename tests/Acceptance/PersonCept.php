<?php

use Tests\Support\AcceptanceTester;

/* @var $scenario Codeception\Scenario */

$random = rand(111, 999);
$name = "name$random";
$surname = "surname$random";
$fullname = "$name $surname";
$email = "$name@$surname.com";
$phone = "($random)$random";

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that person crud works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);

$I->click('Nueva persona');
$I->wait(1);

$I->fillField('Person[name]', $name);
$I->fillField('Person[surname]', $surname);
$I->fillField('Person[email]', $email);
$I->fillField('Person[phone]', $phone);

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->see($fullname);

$I->click($fullname);
$I->wait(1);

$name .= '*';
$fullname = "$name $surname";
$I->fillField('Person[name]', $name);

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->see($fullname);

$I->click('(//a[@title="Eliminar"])[4]');
$I->wait(1);
$I->acceptPopup();
$I->wait(1);

$I->see('exitosamente');

$I->reloadPage();

$I->dontSee($fullname);

