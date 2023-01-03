<?php

use Tests\Support\AcceptanceTester;

$random = rand(111, 999);
$team['name'] = "name$random";
$team['type'] = "Empresa";

$random = rand(111, 999);
$company['name'] = "name$random";
$company['email'] = $company['name'] . "@example.com";
$company['phone'] = "($random)$random";

$random = rand(111, 999);
$sponsor['name'] = "name$random";
$sponsor['surname'] = "surname$random";
$sponsor['email'] = $sponsor['name'] . "@example.com";
$sponsor['phone'] = "($random)$random";

for ($i = 0; $i < 3; $i++) {
    $random = rand(111, 999);
    $person['name'] = "name$random";
    $person['surname'] = "surname$random";
    $person['email'] = $person['name'] . $person['surname'] . "@example.com";
    $person['phone'] = "($random)$random";
    $persons[] = $person;
}

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that team crud works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

// Creo empresa
$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);

$I->click('Nueva empresa');
$I->wait(1);

$I->fillField('Company[name]', $company['name']);
$I->fillField('Company[email]', $company['email']);
$I->fillField('Company[phone]', $company['phone']);

$I->click('Guardar');
$I->wait(1);

// Creo patrocinador

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);

$I->click('Nueva persona');
$I->wait(1);

$I->fillField('Person[name]', $sponsor['name']);
$I->fillField('Person[surname]', $sponsor['surname']);
$I->fillField('Person[email]', $sponsor['email']);
$I->fillField('Person[phone]', $sponsor['phone']);

$I->click('Guardar');
$I->wait(1);

// Creo personas

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);

foreach ($persons as $person) {
    $I->click('Nueva persona');
    $I->wait(1);

    $I->fillField('Person[name]', $person['name']);
    $I->fillField('Person[surname]', $person['surname']);
    $I->fillField('Person[email]', $person['email']);
    $I->fillField('Person[phone]', $person['phone']);

    $I->click('Guardar');
    $I->wait(1);
}

// Creo equipo

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->click('Nuevo equipo');
$I->wait(1);

$I->fillField('Team[name]', $team['name']);
$I->selectOptionForSelect2('Team[team_type_id]', $team['type']);
$I->selectOptionForSelect2('Team[company_id]', $company['name']);
$I->selectOptionForSelect2('Team[sponsor_id]', $sponsor['name']);

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->see($team['name']);

$I->click('Editar');
$I->wait(1);

$team['name'] .= '*';
$I->fillField('Team[name]', $team['name']);

$I->click('Guardar');
$I->wait(1);

$I->see('exitosamente');
$I->see($team['name']);

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->click('(//a[@title="Eliminar"])[1]');
$I->wait(1);
$I->acceptPopup();
$I->wait(1);

$I->see('exitosamente');

$I->reloadPage();

$I->dontSee($team['name']);

