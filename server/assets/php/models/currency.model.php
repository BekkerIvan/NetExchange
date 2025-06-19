<?php

require_once "model.base.php";
class Currency extends Model {
    public function getTableName(): string {
        return "Currency";
    }

    const string ID = "Id";
    const string CODE = "Code";
    const string RATE = "ExchangeRate";
    const string CREATED_DATE_TIME = "CreatedDateTime";
    public function setTableColumns(): void {
        self::$DataModelArr[$this->getTableName()]["Columns"] = [
            self::ID => self::INT,
            self::CODE => $this->varchar(5),
            self::RATE => self::FLOAT,
            self::CREATED_DATE_TIME => self::DATETIME,
        ];
    }

    public function setTableConstraints(): void {
        self::$DataModelArr[$this->getTableName()]["Constraints"] = [
            self::ID => [self::PRIMARY_KEY, self::AUTO_INCREMENT],
            self::CODE => [self::UNIQUE, self::NOT_NULL],
            self::RATE => self::NOT_NULL
        ];
    }

    public function setTableDefaults(): void {
        self::$DataModelArr[$this->getTableName()]["Defaults"] = [
            self::CREATED_DATE_TIME => self::CURRENT_TIMESTAMP
        ];
    }
}