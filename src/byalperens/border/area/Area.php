<?php

namespace byalperens\border\area;

use byalperens\border\area\operation\BorderOperation;
use pocketmine\math\Vector3;
use pocketmine\world\World;

class Area extends BorderOperation{

    /**
     * @param string $name
     * @param World $world
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     */
    public function __construct(
        private string $name,
        private World $world,
        private Vector3 $pos1,
        private Vector3 $pos2
    ){}

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * @return World
     */
    public function getWorld(): World{
        return $this->world;
    }

    /**
     * @return Vector3
     */
    public function getPos1(): Vector3{
        return $this->pos1;
    }

    /**
     * @return Vector3
     */
    public function getPos2(): Vector3{
        return $this->pos2;
    }
}
