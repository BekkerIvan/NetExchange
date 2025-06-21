<?php
abstract class Model implements JsonSerializable {
    // DATA TYPES

    // STRING
    protected const string VARCHAR = "VARCHAR";
    protected const string TEXT = "TEXT";
    protected function varchar(int $Length): string {
        return self::VARCHAR."({$Length})";
    }

    // NUMERICAL
    protected const string BOOL = "BOOL";
    protected const string INT = "INT";
    protected const string FLOAT = "FLOAT";

    // DATES
    protected const string DATE = "DATE";
    protected const string TIME = "TIME";
    protected const string DATETIME = "DATETIME";

    // CONSTRAINTS
    protected const string PRIMARY_KEY = "PRIMARY KEY";
    protected const string AUTO_INCREMENT = "AUTO_INCREMENT";
    protected const string UNIQUE = "UNIQUE";
    protected const string NOT_NULL = "NOT NULL";

    protected const string CURRENT_DATE = "CURRENT_DATE()";
    protected const string CURRENT_TIME = "CURRENT_TIME()";
    protected const string CURRENT_TIMESTAMP = "CURRENT_TIMESTAMP()";

    public abstract function getTableName(): string;

    protected static array $DataModelArr = [];
    public abstract function setTableColumns(): void;
    public abstract function setTableConstraints(): void;
    public abstract function setTableDefaults(): void;
    public abstract function jsonSerialize(): array;
    protected abstract function construct(stdClass $StdClassObj): self;

    protected int $IdInt;
    public abstract function Save(Database $DatabaseObj): self;
    public function load(Database $DatabaseObj, int $Id): ?self {
        $EntityStdObj = $DatabaseObj->query(<<<SQL
            SELECT *
            FROM `{$this->getTableName()}`
            WHERE Id = {$Id}
        SQL, true);
        if (empty($EntityStdObj)) {
            return null;
        }
        return $this->construct($EntityStdObj);
    }
    public function loadAll(Database $DatabaseObj): array {
        $EntityStdObjArr = $DatabaseObj->query(<<<SQL
            SELECT *
            FROM `{$this->getTableName()}`
        SQL);
        $EntityObjArr = [];
        foreach ($EntityStdObjArr as $EntityStdObj) {
            $EntityObj = new static();
            $EntityObjArr[] = $EntityObj->construct($EntityStdObj);
        }
        return $EntityObjArr;
    }

    public static function getDataModel(): array {
        return self::$DataModelArr;
    }

    public static function createDataModel(Database $DatabaseObj): void {
        foreach (self::$DataModelArr as $TableNameStr => $TableDefinitionArr) {
            $ColumnDefinitionArr = [];
            foreach ($TableDefinitionArr["Columns"] as $ColumnNameStr => $DataTypeStr) {
                $ColumnConstraintsStr = "";
                if (!empty($TableDefinitionArr["Constraints"][$ColumnNameStr])) {
                    $ColumnConstraintsStr = match (gettype($TableDefinitionArr["Constraints"][$ColumnNameStr])) {
                        "string" => $TableDefinitionArr["Constraints"][$ColumnNameStr],
                        "array" => implode(" ", $TableDefinitionArr["Constraints"][$ColumnNameStr])
                    };
                }
                $ColumnDefaultsStr = "";
                if (isset($TableDefinitionArr["Defaults"][$ColumnNameStr])) {
                    $ColumnDefaultsStr = "DEFAULT {$TableDefinitionArr["Defaults"][$ColumnNameStr]}";
                }
                $ColumnDefinitionArr[] = "{$ColumnNameStr} {$DataTypeStr} {$ColumnConstraintsStr} {$ColumnDefaultsStr}";
            }

            $ColumnDefinitionStr = implode(",", $ColumnDefinitionArr);
            $DatabaseObj->query(<<<SQL
                CREATE TABLE IF NOT EXISTS `{$TableNameStr}` ({$ColumnDefinitionStr})
            SQL);
        }
    }
}