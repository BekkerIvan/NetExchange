<?php
require_once "currency.base.php";
class USD extends CurrencyBase {
    const string CODE = 'USD';

    public static function getSurchargePercentage(): float {
        return 0.075;
    }
}