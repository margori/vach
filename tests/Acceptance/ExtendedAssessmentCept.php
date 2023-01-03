<?php

use Tests\Support\AcceptanceTester;

$random = rand(111, 999);
$team['name'] = "name$random";

$random = rand(111, 999);
$company['name'] = "company$random";
$company['email'] = $company['name'] . "@example.com";
$company['phone'] = "($random)$random";

$random = rand(111, 999);
$sponsor['name'] = "sponsor$random";
$sponsor['surname'] = "surname$random";
$sponsor['email'] = $sponsor['name'] . "@example.com";
$sponsor['phone'] = "($random)$random";

for ($i = 0; $i < 3; $i++) {
    $random = rand(111, 999);
    $person['name'] = "name$random";
    $person['surname'] = "surname$random";
    $person['email'] = $person['name'] . $person['surname'] . "@example.com";
    $person['phone'] = "($random)$random$random";
    $persons[] = $person;
}

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that extended assessment crud works');

// Agrego licencias

$I->amOnPage(Yii::$app->homeUrl);
$I->loginAsAdmin();

$I->clickMainMenu('Admin', 'Licencias');
$I->wait(1);

$I->click('Agregar licencias');
$I->wait(1);

$I->selectOptionForSelect2('AddModel[coach_id]', 'Coach');
$I->fillField('AddModel[quantity]', count($persons));

$I->click('Guardar');
$I->waitForText('exitosamente');

// Creo empresa

$I->logout();
$I->loginAsCoach();

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
$I->selectOptionForSelect2('Team[team_type_id]', 'Empresa');
$I->selectOptionForSelect2('Team[company_id]', $company['name']);
$I->selectOptionForSelect2('Team[sponsor_id]', $sponsor['name']);

$I->click('Guardar');
$I->wait(1);

for ($i = 0; $i < 3; $i++) {
    // Agrego un miembro
    $I->selectOptionForSelect2('new_member', $persons[$i]['name']);
    $I->click('Agregar');
    $I->wait(1);

    // Completo relevamiento
    if ($i == 0) {
        $I->click('Equipo completado');
    } else {
        $I->click('Actualizar equipo');
    }

    $I->wait(1);

    $I->see('Licencias requeridas: 1');

    $I->click('Completar');
    $I->wait(1);
}

// grab all tokens

for ($i = 0; $i < count($persons); $i++) {
    $wheelToken[$i][0] = $I->grabAttributeFrom('#cell_' . ($i * 3), 'data-token');
    $wheelToken[$i][1] = $I->grabAttributeFrom('#cell_' . ($i * 3 + 1), 'data-token');
    $wheelToken[$i][2] = $I->grabAttributeFrom('#cell_' . ($i * 3 + 2), 'data-token');
}

// Prepare to fill wheels

$I->logout();

// Fill individual wheels
for ($i = 0; $i < count($persons); $i++) {
    $I->fillField('Wheel[token]', $wheelToken[$i][0]);
    $I->wait(1);
    $I->click('Ejecutar');
    $I->wait(1);

    $I->see('Observado: ' . $persons[$i]['name']);
    $I->see('Observador: ' . $persons[$i]['name']);

    $I->click('Comenzar');
    $I->wait(1);

    for ($d = 0; $d < 8; $d++) {
        for ($q = 0; $q < 10; $q++) {
            $answer = 'answer' . (($d * 10) + $q);
            $random = rand(0, 4);
            $I->click("//input[@value=$random and @name='$answer']/..");
        }

        if ($d < 7)
            $I->click('Guardar y próxima dimensión');
        else
            $I->click('Guardar');
        $I->wait(1);
    }

    $I->see('exitosamente');

    $I->click('Inicio');
    $I->wait(1);
}


// Fill group and organizational wheels

for ($w = 1; $w <= 2; $w++) {
    for ($i = 0; $i < count($persons); $i++) {
        $I->fillField('Wheel[token]', $wheelToken[$i][$w]);
        $I->wait(1);
        $I->click('Ejecutar');
        $I->wait(1);

        for ($o = 0; $o < count($persons); $o++) {
            $I->see('Observador: ' . $persons[$i]['name']);
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
        $I->wait(1);
    }
}

$I->loginAsCoach();

$I->see($team['name']);

$I->seeNumberOfElements("//a[text() = '100%']", 3);
