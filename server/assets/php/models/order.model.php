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
            self::BASE_CURRENCY => self::NOT_NULL
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

    protected DateTime $OrderedDateObj;
    protected string $ForeignCurrencyStr;
    protected ?float $ForeignExchangeRateFlt = null;
    protected ?float $ForeignCurrencyAmountFlt = null;
    protected string $BaseCurrencyStr;
    protected ?float $BaseCurrencyAmountFlt = null;
    protected ?float $SurchargePercentageFlt = null;
    protected ?float $SurchargeAmountFlt = null;

    public function __get(string $name) {
        return match ($name) {
            "Id" => $this->IdInt,
            "ForeignCurrency" => $this->ForeignCurrencyStr,
            "ForeignExchangeRate" => $this->ForeignExchangeRateFlt,
            "ForeignCurrencyAmount" => $this->ForeignCurrencyAmountFlt,
            "BaseCurrency" => $this->BaseCurrencyStr,
            "BaseCurrencyAmount" => $this->BaseCurrencyAmountFlt,
            "SurchargePercentage" => $this->SurchargePercentageFlt,
            "SurchargeAmount" => $this->SurchargeAmountFlt,
            "OrderedDate" => $this->OrderedDateObj
        };
    }
    public function __set(string $name, $value): void {
        switch ($name) {
            case "ForeignCurrency":
                $this->ForeignCurrencyStr = (string) $value;
            break;
            case "ForeignExchangeRate":
                $this->ForeignExchangeRateFlt = (float) $value;
            break;
            case "ForeignCurrencyAmount":
                $this->ForeignCurrencyAmountFlt = (float) $value;
            break;
            case "BaseCurrency":
                $this->BaseCurrencyStr = (string) $value;
            break;
            case "BaseCurrencyAmount":
                $this->BaseCurrencyAmountFlt = (float) $value;
            break;
            case "SurchargePercentage":
                $this->SurchargePercentageFlt = (float) $value;
            break;
            case "SurchargeAmount":
                $this->SurchargeAmountFlt = (float) $value;
            break;
            case "OrderedDate":
                if (!($value instanceof DateTime)) {
                    $value = new DateTime($value);
                }
                $this->OrderedDateObj = $value;
            break;
        }
    }
    public function __toString(): string {
        return json_encode($this->jsonSerialize());
    }
    public function jsonSerialize(): array {
        return [
            "Id" => !empty($this->IdInt) ? $this->IdInt : -1,
            "ForeignCurrency" => $this->ForeignCurrencyStr,
            "ForeignExchangeRate" => $this->ForeignExchangeRateFlt,
            "ForeignCurrencyAmount" => $this->ForeignCurrencyAmountFlt,
            "BaseCurrency" => $this->BaseCurrencyStr,
            "BaseCurrencyAmount" => $this->BaseCurrencyAmountFlt,
            "SurchargePercentage" => $this->SurchargePercentageFlt,
            "SurchargeAmount" => $this->SurchargeAmountFlt,
            "OrderedDate" => $this->OrderedDateObj->format(DATE_TIME_FORMAT),
        ];
    }

    protected function construct(stdClass $StdClassObj): self {
        $this->IdInt = $StdClassObj->Id;
        $this->ForeignCurrencyStr = $StdClassObj->ForeignCurrency;
        $this->ForeignExchangeRateFlt = $StdClassObj->ForeignExchangeRate;
        $this->ForeignCurrencyAmountFlt = $StdClassObj->ForeignCurrencyAmount;
        $this->BaseCurrencyStr = $StdClassObj->BaseCurrency;
        $this->BaseCurrencyAmountFlt = $StdClassObj->BaseCurrencyAmount;
        $this->SurchargePercentageFlt = $StdClassObj->SurchargePercentage;
        $this->SurchargeAmountFlt = $StdClassObj->SurchargeAmount;
        $this->OrderedDateObj = $StdClassObj->OrderedDate ? new DateTime($StdClassObj->OrderedDate) : null;
        return $this;
    }

    public function Save(Database $DatabaseObj): self {
        $CreateNewRecordBool = empty($this->IdInt);
        if ($CreateNewRecordBool) {
            $DatabaseObj->query(<<<SQL
                INSERT INTO `{$this->getTableName()}` (ForeignCurrency, ForeignExchangeRate, ForeignCurrencyAmount, BaseCurrency, BaseCurrencyAmount, SurchargePercentage, SurchargeAmount)
                VALUES ('{$this->ForeignCurrencyStr}', {$this->ForeignExchangeRateFlt}, {$this->ForeignCurrencyAmountFlt}, '{$this->BaseCurrencyStr}', {$this->BaseCurrencyAmountFlt}, {$this->SurchargePercentageFlt}, {$this->SurchargeAmountFlt})
            SQL);
            $CurrencyIdObj = $DatabaseObj->query(<<<SQL
                SELECT LAST_INSERT_ID() AS Id
            SQL, true);
            $this->IdInt = $CurrencyIdObj->Id;
        } else {
            $DatabaseObj->query(<<<SQL
                UPDATE `{$this->getTableName()}`
                SET ForeignCurrency = '{$this->ForeignCurrencyStr}', 
                    ForeignExchangeRate = {$this->ForeignExchangeRateFlt},
                    ForeignCurrencyAmount = {$this->ForeignCurrencyAmountFlt},
                    BaseCurrency = '{$this->BaseCurrencyStr}',
                    BaseCurrencyAmount = '{$this->BaseCurrencyAmountFlt}',
                    SurchargePercentage = '{$this->SurchargePercentageFlt}',
                    SurchargeAmount = '{$this->SurchargeAmountFlt}'
                WHERE Id = {$this->IdInt}
            SQL);
        }
        return $this;
    }
    public function load(Database $DatabaseObj, int $Id): ?self {
        $OrderStdObj = $DatabaseObj->query(<<<SQL
            SELECT *
            FROM `{$this->getTableName()}`
            WHERE Id = {$Id}
        SQL, true);
        if (empty($OrderStdObj)) {
            return null;
        }
        return $this->construct($OrderStdObj);
    }
    public function loadAll(Database $DatabaseObj): array {
        $OrderStdObjArr = $DatabaseObj->query(<<<SQL
            SELECT *
            FROM `{$this->getTableName()}`
        SQL);
        $OrderObjArr = [];
        foreach ($OrderStdObjArr as $OrderStdObj) {
            $OrderObj = new self();
            $OrderObjArr[] = $OrderObj->construct($OrderStdObj);
        }
        return $OrderObjArr;
    }
}