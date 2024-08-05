<?php

namespace Tests\Unit\models;

use app\models\Currency;
use Tests\Support\UnitTester;

class CurrencyCest {
    public function _before(UnitTester $I) {
    }

    public function _after(UnitTester $I) {
    }

    public function fetchLastValueRate(UnitTester $I) {
        $lastValue = Currency::getLastValue();

        $I->assertGreaterThan(0, $lastValue);
    }
}
