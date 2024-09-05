<?php

namespace byalperens\border\database;

use sql\libra\SQLite as BaseSQLite;

class SQLite extends BaseSQLite{

    /** @var string */
    public const DATABASE_WORLD_BORDER = "world_border";

    /**
     * @param string $file
     */
    public function __construct(string $file){
        parent::__construct($file);
        $this->execTable(self::DATABASE_WORLD_BORDER, [
            "borderName TEXT",
            "world TEXT",
            "pos1 TEXT",
            "pos2 TEXT"
        ]);
    }
}
