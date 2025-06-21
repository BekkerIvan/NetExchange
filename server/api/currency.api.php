<?php
require_once "../requires.php";
$APIObj = new API([
    "load" => API::GET,
    "loadAll" => API::GET,
    "convert" => API::POST,
]);

if (!$APIObj->validate()) {
    $APIObj->return("Invalid request");
}

$APIObj->run();

function load(API $APIObj): ?Currency {
    $CurrencyId = $APIObj->getPathInfoIndex(1);
    $DatabaseObj = new Database();
    $CurrencyObj = new Currency();
    if (empty($CurrencyId)) {
//        return $CurrencyObj->loadAll($DatabaseObj);
        return null;
    }
    return $CurrencyObj->load($DatabaseObj, $CurrencyId);
}

function loadAll(API $APIObj): array {
    $DatabaseObj = new Database();
    $CurrencyObj = new Currency();
    return $CurrencyObj->loadAll($DatabaseObj);
}

function convert(API $APIObj): array {
    $DatabaseObj = new Database();
    $CurrencyObj = new Currency();
    $CurrencyObj = $CurrencyObj->load($DatabaseObj, $APIObj->getParameter("id") ?? -1);
    if (empty($CurrencyObj)) {
        $APIObj->return("Invalid request");
    }
    require_once $CurrencyObj->CurrencyClassPath;
    $DirectionBool = filter_var($APIObj->getParameter("direction"), FILTER_VALIDATE_BOOLEAN);
    $ValueFlt = $APIObj->getParameter("value");

    /** @var CurrencyBase $CurrencyModelClassStr */
    $CurrencyModelClassStr = $CurrencyObj->Code;
    if ($DirectionBool) {
        $ConvertedValueFlt = $CurrencyModelClassStr::convertFrom($ValueFlt, $CurrencyObj->ExchangeRate);
    } else {
        $ConvertedValueFlt = $CurrencyModelClassStr::convertTo($ValueFlt, $CurrencyObj->ExchangeRate);
    }

    $ReturnArr = [
        "From" => $DirectionBool ? BASE_CURRENCY : $CurrencyObj->Code,
        "To" => !$DirectionBool ? BASE_CURRENCY : $CurrencyObj->Code,
        "FromValue" => (float) $ValueFlt,
        "ToValue" => (float) $ConvertedValueFlt
    ];

    $PaymentValueFlt = $DirectionBool ? $ValueFlt : $ConvertedValueFlt;
    $SurchargePercentageFlt = $CurrencyModelClassStr::getSurchargePercentage();
    if ($SurchargePercentageFlt) {
        $ReturnArr["Surcharge"] = [
            "Percentage" => $SurchargePercentageFlt * 100,
            "Value" => round(($DirectionBool ? $ValueFlt : $ConvertedValueFlt) * $SurchargePercentageFlt, 2),
        ];
        $PaymentValueFlt *= (1 + $SurchargePercentageFlt);
    }
    $ReturnArr["PaymentValue"] = round($PaymentValueFlt, 2);

    return $ReturnArr;
}
