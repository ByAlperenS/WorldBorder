<?php

namespace byalperens\border;

use byalperens\border\area\cache\PosCache;
use byalperens\border\manager\AreaManager;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;

class EventListener implements Listener{

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event): void{
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if (isset(PosCache::$posSelectors[$player->getName()])){
            if (PosCache::$posSelectors[$player->getName()]["pos1"] == null){
                PosCache::$posSelectors[$player->getName()]["pos1"] = $block->getPosition();
                PosCache::$posSelectors[$player->getName()]["world"] = $player->getWorld();
                $player->sendMessage(C::GREEN . "First position successfully selected!");
                $player->sendMessage(C::GREEN . "Break a block to select the second position!");
                $event->cancel();
            }elseif (PosCache::$posSelectors[$player->getName()]["pos2"] == null){
                if ($block->getPosition()->getWorld()->getFolderName() != PosCache::$posSelectors[$player->getName()]["world"]->getFolderName()){
                    $player->sendMessage(C::RED . "The position you choose does not match the world!");
                    return;
                }
                if (PosCache::$posSelectors[$player->getName()]["pos1"]->getY() != $block->getPosition()->getY()){
                    $player->sendMessage(C::RED . "The positions you choose must be at the same height!");
                    return;
                }
                PosCache::$posSelectors[$player->getName()]["pos2"] = $block->getPosition();
                $player->sendMessage(C::GREEN . "Second position successfully selected!");
                $data = PosCache::$posSelectors[$player->getName()];
                AreaManager::addArea($data["borderName"], $data["world"], $data["pos1"], $data["pos2"]);
                $player->sendMessage(C::GREEN . "The area was successfully created!");
                unset(PosCache::$posSelectors[$player->getName()]);
                $event->cancel();
                $area = AreaManager::getArea($data["borderName"]);
                $area->build();
            }
        }
    }
}
