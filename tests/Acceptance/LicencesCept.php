<?php

use Tests\Support\AcceptanceTester;

$initial_stock = 20;
$price = rand(100, 300) / 10;
$add = rand(45, 80);
$remove = rand(10, 44);

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

$I->see('Coach');
$I->see((string) $initial_stock);
$I->see((string) $add);

$valueRaw = $I->grabTextFrom('//table/tbody/tr/td[6]');
$number = str_replace(',', '.', $valueRaw);
$value = floatval($number);
$I->assertGreaterThan(0, $value);

$I->click('Quitar licencias');
$I->wait(1);

$I->selectOptionForSelect2('RemoveModel[coach_id]', 'coach');
$I->fillField('RemoveModel[quantity]', $remove);
$I->wait(1);

$I->click('Guardar');
$I->wait(1);
$I->acceptPopup();
$I->waitForText('exitosamente');

$I->see((string) $initial_stock);
$I->see((string) ($add - $remove));
$I->see((string) $remove);

$I->clickMainMenu('Admin', 'Pagos');
$I->wait(1);

$adminPaymentStatus = $I->grabTextFrom('//table/tbody/tr[1]/td[10]');
$I->assertEquals('pendiente', $adminPaymentStatus);

$I->logout();

$I->loginAsCoach();

$I->clickMainMenu('(coach)', 'Mis licencias');
$I->wait(1);

$I->see((string) $initial_stock);
$I->see((string) ($add - $remove));
$I->see((string) $remove);

$I->clickMainMenu('(coach)', 'Mis pagos');
$I->wait(1);

$I->see(Yii::$app->formatter->asDecimal($price * ($add - $remove)));

$coachPaymentStatus = $I->grabTextFrom('//table/tbody/tr[1]/td[5]');
$I->assertEquals('pendiente', $coachPaymentStatus);
