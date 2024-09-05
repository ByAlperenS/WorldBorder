<?php

namespace byalperens\border;

use byalperens\border\blocks\WorldBorderBlock;
use byalperens\border\commands\WorldBorderCommand;
use byalperens\border\manager\AreaManager;
use byalperens\border\manager\DatabaseManager;
use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\block\Material;
use customiesdevs\customies\block\Model;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;

class WorldBorder extends PluginBase{

    private static self $instance;

    public function onLoad(): void{
        self::$instance = $this;
    }

    public function onEnable(): void{
        $this->getLogger()->info(C::GREEN . "Plugin Enabled!");
        (new DatabaseManager())->init($this->getDataFolder());
        AreaManager::load();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("wborder", new WorldBorderCommand());

        CustomiesBlockFactory::getInstance()->registerBlock(
            static fn () => new WorldBorderBlock(new BlockIdentifier(BlockTypeIds::newId()), "World Border", new BlockTypeInfo(BlockBreakInfo::indestructible())),
            "custom:world_border",
            new Model([new Material(Material::TARGET_ALL, "world_border", Material::RENDER_METHOD_BLEND, true, false)], "geometry.world_border", new Vector3(-8, 0, 6), new Vector3(16, 16, 2))
        );
    }

    public function onDisable(): void{
        AreaManager::save();
    }

    /**
     * @return WorldBorder
     */
    public static function getInstance(): WorldBorder{
        return self::$instance;
    }
}
