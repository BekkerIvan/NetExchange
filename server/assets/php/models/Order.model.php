<?php

require_once "model.base.php";
class Order extends Model {
    public function getTableName(): string {
        return "Order";
    }

    const string ID = "Id";
    const string ORDERED_DATE = "OrderedDate";
    const string FOREIGN_CURRENCY = "ForeignCurrency";
    const string FOREIGN_EXCHANGE_RATE = "ForeignExchangeRate";
    const string FOREIGN_CURRENCY_AMOUNT = "ForeignCurrencyAmount";
    const string BASE_CURRENCY = "BaseCurrency";
    const string BASE_CURRENCY_AMOUNT = "BaseCurrencyAmount";
    const string SURCHARGE_PERCENTAGE = "SurchargePercentage";
    const string SURCHARGE_AMOUNT = "SurchargeAmount";

    public function setTableColumns(): void {
        self::$DataModelArr[$this->getTableName()]["Columns"] = [
            self::ID => self::INT,
            self::ORDERED_DATE => self::DATETIME,
            self::FOREIGN_CURRENCY => $this->varchar(5),
            self::FOREIGN_EXCHANGE_RATE => self::FLOAT,
            self::FOREIGN_CURRENCY_AMOUNT => self::FLOAT,
            self::BASE_CURRENCY => $this->varchar(5),
            self::BASE_CURRENCY_AMOUNT => self::FLOAT,
            self::SURCHARGE_PERCENTAGE => self::FLOAT,
            self::SURCHARGE_AMOUNT => self::FLOAT,
        ];
    }

    public function setTableConstraints(): void {
        self::$DataModelArr[$this->getTableName()]["Constraints"] = [
            self::ID => [self::PRIMARY_KEY, self::AUTO_INCREMENT],
            self::FOREIGN_CURRENCY => self::NOT_NULL,
            self::BASE_CURRENCY => self::NOT_NULL,
        ];
    }

    public function setTableDefaults(): void {
        self::$DataModelArr[$this->getTableName()]["Defaults"] = [
            self::ORDERED_DATE => self::CURRENT_TIMESTAMP,
            self::FOREIGN_CURRENCY_AMOUNT => 0,
            self::BASE_CURRENCY_AMOUNT => 0,
            self::SURCHARGE_AMOUNT => 0,
        ];
    }
}