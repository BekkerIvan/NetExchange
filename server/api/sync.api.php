<?php
require_once "../assets/php/api.class.php";

$APIObj = new API([
    "test" => API::GET
]);

$APIObj->return($_SERVER);


$ModelFileObjArr = new RecursiveDirectoryIterator("../assets/php/models/", FilesystemIterator::SKIP_DOTS);

foreach($ModelFileObjArr as $ModelFileObj) {
    if ($ModelFileObj->isDir()) {
        continue;
    }
    if ($ModelFileObj->getExtension() !== "php") {
        continue;
    }
    $ModelIdentifierStr = ".model.php";
    if (!str_contains($ModelFileObj->getFileName(), $ModelIdentifierStr)) {
        continue;
    }

    $ClassNameStr = str_replace($ModelIdentifierStr, "", $ModelFileObj->getFilename());
    require_once $ModelFileObj->getRealPath();
    if (!class_exists($ClassNameStr)) {
        continue;
    }

    /** @var Model $ModelObj */
    $ModelObj = new $ClassNameStr();
    $ModelObj->setTableColumns();
    $ModelObj->setTableConstraints();
}

//print_r(Model::getDataModel());