<?php

namespace byalperens\border\manager;

use byalperens\border\database\SQLite;

class DatabaseManager{

    private static SQLite $sqlite;

    /**
     * @param string $path
     * @return void
     */
    public function init(string $path): void{
        self::$sqlite = new SQLite($path . "database.db");
    }

    /**
     * @return SQLite
     */
    public static function getSQLite(): SQLite{
        return self::$sqlite;
    }
}
