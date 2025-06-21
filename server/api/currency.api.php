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
    $ConvertFromFlt = $APIObj->getParameter("from");
    $ConvertToFlt = $APIObj->getParameter("to");

    /** @var CurrencyBase $CurrencyModelClassStr */
    $CurrencyModelClassStr = $CurrencyObj->Code;
    if (!empty($ConvertFromFlt)) {
        $ConvertToFlt = $CurrencyModelClassStr::convertFrom($ConvertFromFlt, $CurrencyObj->ExchangeRate);
    } else if (!empty($ConvertToFlt)) {
        $ConvertFromFlt = $CurrencyModelClassStr::convertTo($ConvertToFlt, $CurrencyObj->ExchangeRate);
    }
    return [
        "from" => (float) $ConvertFromFlt,
        "to" => (float) $ConvertToFlt
    ];
}
