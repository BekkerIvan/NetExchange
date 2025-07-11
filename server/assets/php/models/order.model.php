<?php

require_once "model.base.php";
/**
 * @property-read int Id
 * @property-read DateTime OrderedDate
 * @property-read string Reference
 * @property float FinalAmount
 * @property string ForeignCurrency
 * @property string BaseCurrency
 * @property float ForeignExchangeRate
 * @property float ForeignCurrencyAmount
 * @property float BaseCurrencyAmount
 * @property float SurchargePercentage
 * @property float SurchargeAmount
 * @property float DiscountPercentage
 * @property float DiscountAmount
 */
class Order extends Model {
    public function getTableName(): string {
        return "Order";
    }

    const string ID = "Id";
    const string ORDERED_DATE = "OrderedDate";
    const string REFERENCE = "Reference";
    const string FOREIGN_CURRENCY = "ForeignCurrency";
    const string FOREIGN_EXCHANGE_RATE = "ForeignExchangeRate";
    const string FOREIGN_CURRENCY_AMOUNT = "ForeignCurrencyAmount";
    const string BASE_CURRENCY = "BaseCurrency";
    const string BASE_CURRENCY_AMOUNT = "BaseCurrencyAmount";
    const string SURCHARGE_PERCENTAGE = "SurchargePercentage";
    const string SURCHARGE_AMOUNT = "SurchargeAmount";
    const string FINAL_AMOUNT = "FinalAmount";
    const string DISCOUNT_PERCENTAGE = "DiscountPercentage";
    const string DISCOUNT_AMOUNT = "DiscountAmount";

    const int REFERENCE_LENGTH = 15;

    public function setTableColumns(): void {
        self::$DataModelArr[$this->getTableName()]["Columns"] = [
            self::ID => self::INT,
            self::REFERENCE => $this->varchar(self::REFERENCE_LENGTH),
            self::ORDERED_DATE => self::DATETIME,
            self::FOREIGN_CURRENCY => $this->varchar(5),
            self::FOREIGN_EXCHANGE_RATE => self::FLOAT,
            self::FOREIGN_CURRENCY_AMOUNT => self::FLOAT,
            self::BASE_CURRENCY => $this->varchar(5),
            self::BASE_CURRENCY_AMOUNT => self::FLOAT,
            self::SURCHARGE_PERCENTAGE => self::FLOAT,
            self::SURCHARGE_AMOUNT => self::FLOAT,
            self::FINAL_AMOUNT => self::FLOAT,
            self::DISCOUNT_PERCENTAGE => self::FLOAT,
            self::DISCOUNT_AMOUNT => self::FLOAT,
        ];
    }
    public function setTableConstraints(): void {
        self::$DataModelArr[$this->getTableName()]["Constraints"] = [
            self::ID => [self::PRIMARY_KEY, self::AUTO_INCREMENT],
            self::FOREIGN_CURRENCY => self::NOT_NULL,
            self::REFERENCE => [self::NOT_NULL, self::UNIQUE],
            self::BASE_CURRENCY => self::NOT_NULL
        ];
    }
    public function setTableDefaults(): void {
        self::$DataModelArr[$this->getTableName()]["Defaults"] = [
            self::ORDERED_DATE => self::CURRENT_TIMESTAMP,
            self::FOREIGN_CURRENCY_AMOUNT => 0,
            self::BASE_CURRENCY_AMOUNT => 0,
            self::SURCHARGE_AMOUNT => 0,
            self::FINAL_AMOUNT => 0,
            self::DISCOUNT_PERCENTAGE => 0,
            self::DISCOUNT_AMOUNT => 0,
        ];
    }

    protected DateTime $OrderedDateObj;
    protected string $ReferenceStr;
    protected string $ForeignCurrencyStr;
    protected ?float $ForeignExchangeRateFlt = null;
    protected ?float $ForeignCurrencyAmountFlt = null;
    protected string $BaseCurrencyStr;
    protected ?float $BaseCurrencyAmountFlt = null;
    protected ?float $SurchargePercentageFlt = null;
    protected float $SurchargeAmountFlt = 0;
    protected ?float $FinalAmountFlt = null;
    protected float $DiscountPercentageFlt = 0;
    protected float $DiscountAmountFlt = 0;

    public function __get(string $name) {
        return match ($name) {
            "Id" => $this->IdInt,
            "Reference" => $this->ReferenceStr,
            "ForeignCurrency" => $this->ForeignCurrencyStr,
            "ForeignExchangeRate" => $this->ForeignExchangeRateFlt,
            "ForeignCurrencyAmount" => $this->ForeignCurrencyAmountFlt,
            "BaseCurrency" => $this->BaseCurrencyStr,
            "BaseCurrencyAmount" => $this->BaseCurrencyAmountFlt,
            "SurchargePercentage" => $this->SurchargePercentageFlt,
            "SurchargeAmount" => $this->SurchargeAmountFlt,
            "OrderedDate" => $this->OrderedDateObj,
            "FinalAmount" => $this->FinalAmountFlt,
            "DiscountPercentage" => $this->DiscountPercentageFlt,
            "DiscountAmount" => $this->DiscountAmountFlt,
        };
    }
    public function __set(string $name, $value): void {
        switch ($name) {
            case "FinalAmount":
                $this->FinalAmountFlt = (string) $value;
            break;
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
            case "DiscountPercentage":
                $this->DiscountPercentageFlt = (float) $value;
            break;
            case "DiscountAmount":
                $this->DiscountAmountFlt = (float) $value;
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
            "Reference" => $this->ReferenceStr,
            "ForeignCurrency" => $this->ForeignCurrencyStr,
            "ForeignExchangeRate" => $this->ForeignExchangeRateFlt,
            "ForeignCurrencyAmount" => $this->ForeignCurrencyAmountFlt,
            "BaseCurrency" => $this->BaseCurrencyStr,
            "BaseCurrencyAmount" => $this->BaseCurrencyAmountFlt,
            "SurchargePercentage" => $this->SurchargePercentageFlt,
            "SurchargeAmount" => $this->SurchargeAmountFlt,
            "FinalAmount" => $this->FinalAmountFlt,
            "DiscountAmount" => $this->DiscountAmountFlt,
            "DiscountPercentage" => $this->DiscountPercentageFlt,
            "OrderedDate" => $this->OrderedDateObj->format(DATE_TIME_FORMAT),
        ];
    }

    protected function construct(stdClass $StdClassObj): self {
        $this->IdInt = $StdClassObj->Id;
        $this->ReferenceStr = $StdClassObj->Reference;
        $this->FinalAmountFlt = $StdClassObj->FinalAmount;
        $this->ForeignCurrencyStr = $StdClassObj->ForeignCurrency;
        $this->ForeignExchangeRateFlt = $StdClassObj->ForeignExchangeRate;
        $this->ForeignCurrencyAmountFlt = $StdClassObj->ForeignCurrencyAmount;
        $this->BaseCurrencyStr = $StdClassObj->BaseCurrency;
        $this->BaseCurrencyAmountFlt = $StdClassObj->BaseCurrencyAmount;
        $this->SurchargePercentageFlt = $StdClassObj->SurchargePercentage;
        $this->SurchargeAmountFlt = $StdClassObj->SurchargeAmount;
        $this->DiscountPercentageFlt = $StdClassObj->DiscountPercentage;
        $this->DiscountAmountFlt = $StdClassObj->DiscountAmount;
        $this->OrderedDateObj = $StdClassObj->OrderedDate ? new DateTime($StdClassObj->OrderedDate) : null;
        return $this;
    }

    public function generateReference(Database $DatabaseObj): string {
        $CharactersStr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $CharLengthInt = strlen($CharactersStr);
        $ReferenceStr = "";
        do {
            for ($i = 0; $i < self::REFERENCE_LENGTH; $i++) {
                $ReferenceStr .= $CharactersStr[random_int(0, $CharLengthInt - 1)];
            }
        } while (!empty($this->loadByReference($DatabaseObj, $ReferenceStr)));
        return $ReferenceStr;
    }

    public function calculateFinalAmount(): float {
        return round($this->BaseCurrencyAmountFlt + $this->SurchargeAmountFlt, 2);
    }

    public function Save(Database $DatabaseObj): self {
        $CreateNewRecordBool = empty($this->IdInt);
        if ($CreateNewRecordBool) {
            $this->ReferenceStr = $this->generateReference($DatabaseObj);
            $this->OrderedDateObj = new DateTime();
            $DatabaseObj->query(<<<SQL
                INSERT INTO `{$this->getTableName()}` (DiscountPercentage, DiscountAmount, FinalAmount, Reference, ForeignCurrency, ForeignExchangeRate, ForeignCurrencyAmount, BaseCurrency, BaseCurrencyAmount, SurchargePercentage, SurchargeAmount)
                VALUES ({$this->DiscountPercentageFlt}, {$this->DiscountAmountFlt}, {$this->FinalAmountFlt}, '{$this->ReferenceStr}', '{$this->ForeignCurrencyStr}', {$this->ForeignExchangeRateFlt}, {$this->ForeignCurrencyAmountFlt}, '{$this->BaseCurrencyStr}', {$this->BaseCurrencyAmountFlt}, {$this->SurchargePercentageFlt}, {$this->SurchargeAmountFlt})
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
                    SurchargeAmount = '{$this->SurchargeAmountFlt}',
                    DiscountPercentage = '{$this->DiscountPercentageFlt}',
                    DiscountAmount = '{$this->DiscountAmountFlt}'
                WHERE Id = {$this->IdInt}
            SQL);
        }
        return $this;
    }
    public function loadByReference(Database $DatabaseObj, string $ReferenceStr): ?self {
        $OrderStdObj = $DatabaseObj->query(<<<SQL
            SELECT *
            FROM `{$this->getTableName()}`
            WHERE Reference = '{$ReferenceStr}'
        SQL, true);
        if (empty($OrderStdObj)) {
            return null;
        }
        return $this->construct($OrderStdObj);
    }

}