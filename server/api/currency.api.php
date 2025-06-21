<?php
require_once "../requires.php";
$APIObj = new API([
    "load" => API::GET,
    "loadAll" => API::GET,
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
