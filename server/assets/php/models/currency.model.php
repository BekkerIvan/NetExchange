<?php

require_once "model.base.php";

/**
 * @property-read int Id
 * @property-read ?DateTime CreatedDateTime
 * @property string Code
 * @property float ExchangeRate
 */
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

    protected string $CodeStr;
    protected float $ExchangeRateFlt;
    protected ?DateTime $CreatedDateTimeObj = null;

    public function __get(string $name) {
        return match ($name) {
            "Id" => $this->IdInt,
            "Code" => $this->CodeStr,
            "ExchangeRate" => $this->ExchangeRateFlt,
            "CreatedDateTime" => $this->CreatedDateTimeObj,
        };
    }
    public function __set(string $name, $value): void {
        switch ($name) {
            case "Code":
                $this->CodeStr = (string) $value;
            break;
            case "ExchangeRate":
                $this->ExchangeRateFlt = (float) $value;
            break;
        }
    }
    public function __toString(): string {
        return json_encode($this->jsonSerialize());
    }
    public function jsonSerialize(): array {
        return [
            "Id" => !empty($this->IdInt) ? $this->IdInt : -1,
            "Code" => $this->CodeStr,
            "ExchangeRate" => $this->ExchangeRateFlt,
            "CreatedDateTime" => $this->CreatedDateTimeObj?->format(DATE_TIME_FORMAT),
        ];
    }

    protected function construct(stdClass $StdClassObj): self {
        $this->IdInt = $StdClassObj->Id;
        $this->CodeStr = $StdClassObj->Code;
        $this->ExchangeRateFlt = $StdClassObj->ExchangeRate;
        $this->CreatedDateTimeObj = $StdClassObj->CreatedDateTime ? new DateTime($StdClassObj->CreatedDateTime) : null;
        return $this;
    }

    public function Save(Database $DatabaseObj): self {
        $CreateNewRecordBool = empty($this->IdInt);
        if ($CreateNewRecordBool) {
            $this->CreatedDateTimeObj = new DateTime();
            $DatabaseObj->query(<<<SQL
                INSERT INTO `{$this->getTableName()}` (Code, ExchangeRate, CreatedDateTime) VALUES ('{$this->CodeStr}', {$this->ExchangeRateFlt}, '{$this->CreatedDateTimeObj->format(DATE_TIME_FORMAT)}')
            SQL);
            $CurrencyIdObj = $DatabaseObj->query(<<<SQL
                SELECT LAST_INSERT_ID() AS Id
            SQL, true);
            $this->IdInt = $CurrencyIdObj->Id;
        } else {
            $DatabaseObj->query(<<<SQL
                UPDATE `{$this->getTableName()}`
                SET Code = '{$this->CodeStr}', 
                    ExchangeRate = {$this->ExchangeRateFlt}
                WHERE Id = {$this->IdInt}
            SQL);
        }
        return $this;
    }
}