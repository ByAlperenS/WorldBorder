<?php

namespace byalperens\border\blocks;

use customiesdevs\customies\block\permutations\Permutable;
use customiesdevs\customies\block\permutations\RotatableTrait;
use pocketmine\block\Transparent;

class WorldBorderBlock extends Transparent implements Permutable{
    use RotatableTrait;
}
