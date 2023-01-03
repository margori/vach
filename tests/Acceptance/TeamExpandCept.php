<?php

use Tests\Support\AcceptanceTester;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that team expansion works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

$I->click('ACME Núcleo Inicial');
$I->wait(1);

$I->seeNumberOfElements("//strong[contains(text(), 'enviar')]", 3 * 3);

$I->selectOptionForSelect2('new_member', 'Dallas');
$I->click('Agregar');
$I->wait(1);

$I->click('Actualizar equipo');
$I->wait(1);

$I->see('Licencias requeridas: 1');

$I->click('Completar');
$I->wait(1);

$I->see('exitosamente');
$I->wait(1);

$I->seeNumberOfElements("//strong[contains(text(), 'enviar')]", 4 * 3);

