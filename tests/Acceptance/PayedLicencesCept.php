<?php

use Tests\Support\AcceptanceTester;

$initial_stock = 20;
$price = rand(100, 300) / 10;
$add = rand(20, 40);

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
$I->checkOption('AddModel[payed]');
$I->wait(1);

$I->click('Guardar');
$I->waitForText('exitosamente');

$I->see('Coach');
$I->see((string) $add);

$valueRaw = $I->grabTextFrom('//table/tbody/tr[1]/td[6]');
$number = str_replace(',', '.', $valueRaw);
$value = floatval($number);
$I->assertGreaterThan(0, $value);

$I->clickMainMenu('Admin', 'Pagos');
$I->wait(1);

$adminPaymentStatus = $I->grabTextFrom('//table/tbody/tr[1]/td[10]');
$I->assertEquals('pagado', $adminPaymentStatus);

$I->logout();

$I->loginAsCoach();

$I->clickMainMenu('(coach)', 'Mis licencias');
$I->wait(1);

$I->see((string) $initial_stock);
$I->see((string) $add);

$I->clickMainMenu('(coach)', 'Mis pagos');
$I->wait(1);

$I->see(Yii::$app->formatter->asDecimal($price * $add));

$coachPaymentStatus = $I->grabTextFrom('//table/tbody/tr[1]/td[5]');
$I->assertEquals('pagado', $coachPaymentStatus);
