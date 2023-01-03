<?php

use Tests\Support\AcceptanceTester;

$random = rand(111, 999);
$team['name'] = "name$random";
$team['type'] = "Empresa";
$company['name'] = 'ACME';
$sponsor['name'] = 'Patricio';

$persons = [
    ['name' => 'Ariel'],
    ['name' => 'Beatriz'],
    ['name' => 'Carlos'],
];

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that assessment can be share safely');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

// Creo nuevo relevamiento

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

// Agrego miembros

foreach ($persons as $person) {
    $I->selectOptionForSelect2('new_member', $person['name']);
    $I->click('Agregar');
    $I->wait(1);
}


$I->click('Equipo completado');
$I->wait(1);

$I->see('Licencias requeridas: ' . count($persons));

$I->click('Completar');
$I->wait(1);

$I->selectOptionForSelect2('coach_id', 'assisstant');
$I->click('Permitir acceso');
$I->wait(1);

$I->see('Acceso concedido');

$I->logout();

$I->loginAsAssisstant();

$I->see($team['name']);

$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);

$I->dontSee('ACME');

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);

$I->dontSee('Ariel');
$I->dontSee('Beatriz');
$I->dontSee('Carlos');

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->dontSee('Núcleo');

$I->click($team['name']);
$I->wait(1);

$I->click('Ir al informe...');
$I->wait(1);
$I->waitForText('Ariel A');
$I->waitForText('Beatriz B');
$I->waitForText('Carlos C');

$I->clickMainMenu('Clientes', 'Tablero');
$I->wait(1);

$I->selectOptionForSelect2('DashboardFilter[companyId]', 'ACME');
$I->wait(1);
$I->selectOptionForSelect2('DashboardFilter[teamId]', $team['name']);
$I->wait(1);
$I->selectOptionForSelect2('DashboardFilter[memberId]', 'Carlos C');
$I->wait(1);

$I->logout();

$I->loginAsAdmin();

$I->dontSee($team['name']);

$I->clickMainMenu('Clientes', 'Empresas');
$I->wait(1);
$I->dontSee('ACME');

$I->clickMainMenu('Clientes', 'Personas');
$I->wait(1);
$I->dontSee('Ariel');
$I->dontSee('Beatriz');
$I->dontSee('Carlos');

$I->clickMainMenu('Clientes', 'Tablero');
$I->wait(1);

$I->dontSee('ACME');
$I->dontSee($team['name']);
$I->dontSee('Carlos C');
