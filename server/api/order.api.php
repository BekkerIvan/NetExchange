<?php
require_once "../requires.php";
$APIObj = new API([
    "load" => API::GET,
    "loadAll" => API::GET,
    "place" => API::POST,
]);

if (!$APIObj->validate()) {
    $APIObj->return("Invalid request");
}

$APIObj->run();

function load(API $APIObj): ?Order {
    $OrderId = $APIObj->getPathInfoIndex(1);
    $DatabaseObj = new Database();
    $OrderObj = new Order();
    if (empty($OrderId)) {
//        return $OrderObj->loadAll($DatabaseObj);
        return null;
    }
    return $OrderObj->load($DatabaseObj, $OrderId);
}

function loadAll(API $APIObj): array {
    $DatabaseObj = new Database();
    $OrderObj = new Order();
    $DatabaseObj::$DebugMode = true;
    return $OrderObj->loadAll($DatabaseObj);
}

function place(API $APIObj): Order {
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

    $SurchargePercentageFlt = $CurrencyModelClassStr::getSurchargePercentage();
    $SurchargeAmountFlt = 0;
    if ($SurchargePercentageFlt) {
        $SurchargeAmountFlt = round(($DirectionBool ? $ValueFlt : $ConvertedValueFlt) * $SurchargePercentageFlt, 2);
    }

    $OrderObj = new Order();
    $OrderObj->ForeignCurrency = $CurrencyObj->Code;
    $OrderObj->ForeignExchangeRate = $CurrencyObj->ExchangeRate;
    $OrderObj->ForeignCurrencyAmount = $DirectionBool ? $ConvertedValueFlt : $ValueFlt;
    $OrderObj->BaseCurrency = BASE_CURRENCY;
    $OrderObj->BaseCurrencyAmount = !$DirectionBool ? $ConvertedValueFlt : $ValueFlt;
    $OrderObj->SurchargeAmount = $SurchargeAmountFlt;
    $OrderObj->SurchargePercentage = $CurrencyModelClassStr::getSurchargePercentage();
    $OrderObj->FinalAmount = $OrderObj->calculateFinalAmount();
    if (!$CurrencyModelClassStr::doBeforeOrderPlaced($OrderObj)) {
        $APIObj->return("Order has been canceled.");
    }
    $OrderObj = $OrderObj->Save($DatabaseObj);
    if (!$CurrencyModelClassStr::doAfterOrderPlaced($OrderObj)) {
        $OrderObj->Delete($DatabaseObj);
        $APIObj->return("Invalid request");
    }
    return $OrderObj;
}
