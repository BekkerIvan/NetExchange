<?php
abstract class Model {
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

    public static function getDataModel(): array {
        return self::$DataModelArr;
    }

    public static function createDataModelSql(): string {
        $CreateTableSQLArr = [];
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
            $CreateTableSQLArr[$TableNameStr] =  <<<SQL
                CREATE TABLE `{$TableNameStr}` ({$ColumnDefinitionStr})
            SQL;
        }
        return implode(";", $CreateTableSQLArr);
    }
}