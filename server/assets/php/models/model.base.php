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

    public abstract function getTableName(): string;

    protected static array $DataModelArr = [];
    public abstract function setTableColumns(): void;
    public abstract function setTableConstraints(): void;

    public static function getDataModel(): array {
        return self::$DataModelArr;
    }
}