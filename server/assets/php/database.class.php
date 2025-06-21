<?php

class Database {
    public static bool $DebugMode = false;
    protected static ?mysqli $DatabaseObj = null;
    protected function connect(): void {
        if (self::$DebugMode) {
            error_log(print_r("Connecting to database: ".getenv("DB_DATABASE"), true));
        }
        self::$DatabaseObj = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_PORT"));
        if (self::$DatabaseObj->connect_errno) {
            throw new Exception(self::$DatabaseObj->connect_errno);
        }
    }

    public function query(string $SQL, bool $ReturnSingleObjectBool = false): null|array|stdClass {
        if (!self::$DatabaseObj) {
            self::connect();
        }
        if (self::$DebugMode) {
            error_log(print_r($SQL, true));
        }
        $QueryResultObj = self::$DatabaseObj->query($SQL);
        if (is_bool($QueryResultObj)) {
            return [
                "affected_rows" => self::$DatabaseObj->affected_rows
            ];
        }
        if (self::$DatabaseObj->error) {
            throw new Exception("{$this::$DatabaseObj->error}::{$this::$DatabaseObj->error} - {$SQL}");
        }

        if ($ReturnSingleObjectBool) {
            return $QueryResultObj->fetch_object();
        }

        $DataArr = [];
        while ($Row = $QueryResultObj->fetch_object()) {
            $DataArr[] = $Row;
        }
        return $DataArr;
    }
}