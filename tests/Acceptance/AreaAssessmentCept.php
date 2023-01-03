<?php

use Tests\Support\AcceptanceTester;

$random = rand(111, 999);
$team['name'] = "name$random";

$company['name'] = "ACME";
$sponsor['name'] = "Patricio";

$persons = [
    ['name' => 'Ariel'],
    ['name' => 'Beatriz'],
    ['name' => 'Carlos'],
];

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that assessment crud works');

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsCoach();

// Creo equipo

$I->clickMainMenu('Clientes', 'Equipos');
$I->wait(1);

$I->click('Nuevo equipo');
$I->wait(1);

$I->fillField('Team[name]', $team['name']);
$I->selectOptionForSelect2('Team[team_type_id]', 'Áreas');
$I->selectOptionForSelect2('Team[company_id]', $company['name']);
$I->selectOptionForSelect2('Team[sponsor_id]', $sponsor['name']);

$I->click('Guardar');
//$I->wait(1);

// Agrego miembros

foreach ($persons as $person) {
    $I->selectOptionForSelect2('new_member', $person['name']);
    $I->click('Agregar');
    $I->wait(1);
}

// Creo nuevo relevamiento

$I->click('Equipo completado');
$I->wait(1);

$I->see('Licencias requeridas: ' . count($persons));

$I->click('Completar');
$I->wait(1);

// grab all tokens

for ($i = 0; $i < count($persons); $i++) {
    $wheelToken[$i] = $I->grabAttributeFrom('#cell_' . $i, 'data-token');
}

// Prepare to fill wheels

$I->logout();

// Fill area wheels

for ($i = 0; $i < count($persons); $i++) {
    $I->fillField('Wheel[token]', $wheelToken[$i]);
    //$I->wait(1);
    $I->click('Ejecutar');
    $I->wait(1);

    for ($o = 0; $o < count($persons); $o++) {
        $I->see('Observador: ' . $persons[$i % 3]['name']);
        $I->see('Observado: ' . $persons[$o]['name']);

        $I->click('Comenzar');
        $I->wait(1);

        for ($d = 0; $d < 8; $d++) {
            for ($q = 0; $q < 8; $q++) {
                $answer = 'answer' . (($d * 8) + $q);
                $random = rand(0, 4);
                $I->click("//input[@value=$random and @name='$answer']/..");
            }

            if ($d < 7)
                $I->click('Guardar y próxima competencia');
            else
                $I->click('Guardar');
            $I->wait(1);
        }
    }

    $I->see('exitosamente');

    $I->click('Inicio');
    //$I->wait(1);
}

// Delete this assessment

$I->loginAsCoach();

$I->see($team['name']);

$I->seeNumberOfElements("//a[text() = '100%']", 1);
