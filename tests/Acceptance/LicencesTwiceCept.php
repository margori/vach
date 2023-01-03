<?php

use Tests\Support\AcceptanceTester;

$initial_stock = 20;
$price = rand(100, 300) / 10;
$add = rand(10, 20);
$remove = rand(1, 9);

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that licence management works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsAdmin();

$I->clickMainMenu('Admin', 'Licencias');
$I->wait(1);

$I->click('Agregar licencias');
$I->wait(1);

$I->fillField('AddModel[price]', $price);
$I->fillField('AddModel[quantity]', $add);
$I->selectOptionForSelect2('AddModel[coach_id]', 'coach');
$I->wait(1);

$I->click('Guardar');
$I->waitForText('exitosamente');

$I->click('Agregar licencias');
$I->wait(1);

$I->fillField('AddModel[price]', $price);
$I->fillField('AddModel[quantity]', $add);
$I->selectOptionForSelect2('AddModel[coach_id]', 'coach');
$I->wait(1);

$I->click('Guardar');
$I->waitForText('exitosamente');

$I->see('Coach');
$I->see((string) $add);

$valueRaw1 = $I->grabTextFrom('//table/tbody/tr[1]/td[6]');
$valueRaw2 = $I->grabTextFrom('//table/tbody/tr[2]/td[6]');
$I->assertEquals($valueRaw1, $valueRaw2);
