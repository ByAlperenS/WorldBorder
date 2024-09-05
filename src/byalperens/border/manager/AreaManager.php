<?php

namespace byalperens\border\manager;

use byalperens\border\area\Area;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\World;

class AreaManager{

    /** @var Area[] */
    private static array $areas = [];

    public static function load(): void{
        $sqlite = DatabaseManager::getSQLite();

        if (!empty($sqlite->get([], $sqlite::DATABASE_WORLD_BORDER, [], [], true))){
            foreach ($sqlite->get([], $sqlite::DATABASE_WORLD_BORDER, [], [], true) as $data){
                $pos1 = json_decode($data["pos1"], true);
                $pos2 = json_decode($data["pos2"], true);

                if (!Server::getInstance()->getWorldManager()->isWorldLoaded($data["world"])) Server::getInstance()->getWorldManager()->loadWorld($data["world"]);
                self::addArea($data["borderName"], Server::getInstance()->getWorldManager()->getWorldByName($data["world"]), new Vector3($pos1[0], $pos1[1], $pos1[2]), new Vector3($pos2[0], $pos2[1], $pos2[2]));
                $sqlite->delete([":borderName" => $data["borderName"]], $sqlite::DATABASE_WORLD_BORDER, ["borderName"]);
            }
        }
    }

    public static function save(): void{
        if (!empty(self::$areas)){
            $sqlite = DatabaseManager::getSQLite();

            foreach (self::$areas as $borderName => $area){
                $sqlite->insert([":borderName" => $borderName, "world" => $area->getWorld()->getFolderName(), "pos1" => json_encode([$area->getPos1()->getX(), $area->getPos1()->getY(), $area->getPos1()->getZ()]), "pos2" => json_encode([$area->getPos2()->getX(), $area->getPos2()->getY(), $area->getPos2()->getZ()])], $sqlite::DATABASE_WORLD_BORDER, ["borderName", "world", "pos1", "pos2"], [":borderName", ":world", ":pos1", ":pos2"]);
            }
        }
    }

    /**
     * @param string $borderName
     * @return Area|null
     */
    public static function getArea(string $borderName): ?Area{
        return self::$areas[$borderName] ?? null;
    }

    /**
     * @return Area[]
     */
    public static function getAllAreas(): array{
        return self::$areas;
    }

    /**
     * @param string $borderName
     * @param World $world
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @return bool
     */
    public static function addArea(string $borderName, World $world, Vector3 $pos1, Vector3 $pos2): bool{
        if (self::getArea($borderName) != null) return false;
        self::$areas[$borderName] = new Area($borderName, $world, $pos1, $pos2);
        return true;
    }

    /**
     * @param string $borderName
     * @return bool
     */
    public static function deleteArea(string $borderName): bool{
        if (self::getArea($borderName) == null) return false;
        unset(self::$areas[$borderName]);
        return true;
    }
}
