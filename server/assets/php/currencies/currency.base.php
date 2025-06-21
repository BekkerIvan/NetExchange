<?php
abstract class CurrencyBase {
    public static function convertFrom(float $ValueFlt, float $ExchangeRateFlt, int $RoundInt = 2): float {
        return round($ValueFlt * $ExchangeRateFlt, $RoundInt);
    }
    public static function convertTo(float $ValueFlt, float $ExchangeRateFlt, int $RoundInt = 2): float {
        return round($ValueFlt / $ExchangeRateFlt, $RoundInt);
    }

    public static function format(float $ValueFlt, int $DecimalsInt = 2, string $DecimalSeparatorStr = ".", $ThousandSeparatorStr = " "): string {
        return number_format($ValueFlt, $DecimalsInt, $DecimalSeparatorStr, $ThousandSeparatorStr);
    }

    public static function getSurchargePercentage(): float {
        return 0;
    }

    public static function doBeforeOrderPlaced(Order $OrderObj): bool {
        return true;
    }

    public static function doAfterOrderPlaced(Order $OrderObj): bool {
        return true;
    }
}