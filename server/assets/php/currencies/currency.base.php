<?php
abstract class CurrencyBase {
    public static function convert(float $ValueFlt, float $ExchangeRateFlt, int $RoundInt = 2): float {
        return round($ValueFlt * $ExchangeRateFlt, $RoundInt);
    }

    public static function format(float $ValueFlt, int $DecimalsInt = 2, string $DecimalSeparatorStr = ".", $ThousandSeparatorStr = " "): string {
        return number_format($ValueFlt, $DecimalsInt, $DecimalSeparatorStr, $ThousandSeparatorStr);
    }
}