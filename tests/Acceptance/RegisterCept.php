<?php

use Tests\Support\AcceptanceTester;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that register works');
$I->amOnPage(Yii::$app->homeUrl);

if (Yii::$app->params['allow_register']) {
    $email = 'jhon@dow.com';
    $username = 'jhon.dow';
    $password = '12345678';

    $I->click('Crear cuenta');
    $I->wait(1);

    $I->see('Crear cuenta');

    $I->fillField('User[name]', 'Jhon');
    $I->fillField('User[surname]', 'Dow');
    $I->fillField('User[email]', $email);
    $I->fillField('User[username]', $username);
    $I->fillField('User[password]', $password);
    $I->fillField('User[password_confirm]', '12345678');
    $I->click('Crear');
    $I->wait(1);

    $I->see('(jhon.dow)');

    $I->logout();
    $I->see('Nombre usuario');

    $I->login($username, $password);
    $I->wait(1);

    $I->see('(jhon.dow)');
} else {
    $I->dontSee('Crear cuenta');
}