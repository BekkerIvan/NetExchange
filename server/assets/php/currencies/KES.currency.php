<?php
require_once "currency.base.php";
class KES extends CurrencyBase {
    const string CODE = 'KES';

    public static function getSurchargePercentage(): float {
        return 0.025;
    }
}