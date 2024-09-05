<?php

namespace byalperens\border\commands;

use byalperens\border\area\cache\PosCache;
use byalperens\border\manager\AreaManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

class WorldBorderCommand extends Command{

    public function __construct(){
        parent::__construct("wborder", "World Border Commands", C::DARK_GREEN . "Usage: " . C::GREEN . "/wborder help");
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
        $this->setPermissionMessage(C::RED . "You don't have permission for this command!");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if (!$sender instanceof Player){
            return;
        }
        if (isset($args[0])){
            if ($args[0] == "help"){
                $sender->sendMessage(C::MINECOIN_GOLD . "World Border");
                $sender->sendMessage(C::GRAY . "/wborder help");
                $sender->sendMessage(C::GRAY . "/wborder create [name]");
                $sender->sendMessage(C::GRAY . "/wborder delete [name]");
                $sender->sendMessage(C::GRAY . "/wborder list");
            }
            if ($args[0] == "create"){
                if (isset($args[1])){
                    if (AreaManager::getArea($args[1]) != null){
                        $sender->sendMessage(C::RED . "This area is already exists!");
                        return;
                    }
                    PosCache::$posSelectors[$sender->getName()] =[
                        "borderName" => $args[1],
                        "world" => null,
                        "pos1" => null,
                        "pos2" => null
                    ];
                    $sender->sendMessage(C::GREEN . "Break a block to select the first position!");
                }else{
                    $sender->sendMessage($this->getUsage());
                }
            }
            if ($args[0] == "delete"){
                if (isset($args[1])){
                    if (($area = AreaManager::getArea($args[1])) == null){
                        $sender->sendMessage(C::RED . "The area not found!");
                        return;
                    }
                    if (!AreaManager::deleteArea($args[1])){
                        $sender->sendMessage(C::RED . "There was an error while deleting the area!");
                        return;
                    }
                    $area->delete();
                    $sender->sendMessage(C::GREEN . "The area has been deleted!");
                }else{
                    $sender->sendMessage($this->getUsage());
                }
            }
            if ($args[0] == "list"){
                $sender->sendMessage(C::DARK_GRAY . "Loading data...");
                $sender->sendMessage(C::MINECOIN_GOLD . "World Border List");

                if (!empty(AreaManager::getAllAreas())){
                    foreach (array_keys(AreaManager::getAllAreas()) as $borderName){
                        $sender->sendMessage(C::GRAY . $borderName);
                    }
                }
            }
        }else{
            $sender->sendMessage($this->getUsage());
        }
    }
}
