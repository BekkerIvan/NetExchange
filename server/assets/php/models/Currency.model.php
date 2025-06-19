<?php

require_once "model.base.php";
class Currency extends Model {
    public function getTableName(): string {
        return "Currency";
    }

    public function setTableColumns(): void {
        self::$DataModelArr[$this->getTableName()]["Columns"] = [
            "Id" => self::INT,
            ""
        ];
    }

    public function setTableConstraints(): void {
        self::$DataModelArr[$this->getTableName()]["Constraints"] = [
            "Id" => self::PRIMARY_KEY
        ];
    }
}