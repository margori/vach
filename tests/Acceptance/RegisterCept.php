<?php

use Tests\Support\AcceptanceTester;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that register works');
$I->amOnPage(Yii::$app->homeUrl);

if (Yii::$app->params['allow_register']) {
    $I->click('Crear cuenta');
    $I->wait(1);

    $I->see('Crear cuenta');

    $I->fillField('User[name]', 'Jhon');
    $I->fillField('User[surname]', 'Dow');
    $I->fillField('User[email]', 'jhon@dow.com');
    $I->fillField('User[username]', 'jhon.dow');
    $I->fillField('User[password]', '12345678');
    $I->fillField('User[password_confirm]', '12345678');
    $I->click('Crear');
    $I->wait(1);

    $I->see('(jhon.dow)');
} else {
    $I->dontSee('Crear cuenta');
}