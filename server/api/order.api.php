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
