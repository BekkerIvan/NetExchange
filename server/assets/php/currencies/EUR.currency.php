<?php
require_once "currency.base.php";
class EUR extends CurrencyBase {
    const string CODE = 'EUR';

    public static function getSurchargePercentage(): float {
        return 0.05;
    }
}