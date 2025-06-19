<?php

class Database {
    protected static ?mysqli $DatabaseObj = null;
    protected function connect(): void {
        self::$DatabaseObj = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_PORT"));
        if (self::$DatabaseObj->connect_errno) {
            throw new Exception(self::$DatabaseObj->connect_errno);
        }
    }

    public function query(string $SQL) {
        if (!self::$DatabaseObj) {
            self::connect();
        }
    }
}