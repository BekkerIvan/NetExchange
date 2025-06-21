<?php
require_once "currency.base.php";
class EUR extends CurrencyBase {
    const string CODE = 'EUR';

    public static function getSurchargePercentage(): float {
        return 0.05;
    }

    public static function doBeforeOrderPlaced(Order $OrderObj): bool {
        $OrderObj->DiscountPercentage = 0.02;
        $OrderObj->DiscountAmount = round($OrderObj->FinalAmount * $OrderObj->DiscountPercentage, 2);
        $OrderObj->FinalAmount -= $OrderObj->DiscountAmount;
        return parent::doBeforeOrderPlaced($OrderObj);
    }
}