<?php

namespace byalperens\border\math;

use byalperens\border\blocks\WorldBorderBlock;
use customiesdevs\customies\block\permutations\RotatableTrait;
use pocketmine\block\Block;
use pocketmine\block\utils\HorizontalFacingTrait;

class AreaMath{

    /**
     * @param array $pos1
     * @param array $pos2
     * @return array[]
     */
    public static function calculateHallowArea(array $pos1, array $pos2): array{
        $newPos1 = [];

        if (($pos1[0] - 1) > min($pos1[0], $pos2[0]) && ($pos1[1] - 1) < max($pos1[0], $pos2[0])){
            $newPos1 = [$pos1[0] - 1];
        }elseif (($pos1[0] + 1) > min($pos1[0], $pos2[0]) && ($pos1[1] + 1) < max($pos1[0], $pos2[0])){
            $newPos1 = [$pos1[0] + 1];
        }
        if (($pos1[2] - 1) > min($pos1[2], $pos2[2]) && ($pos1[2] - 1) < max($pos1[2], $pos2[2])){
            $newPos1[1] = $pos1[2] - 1;
        }elseif (($pos1[2] + 1) > min($pos1[2], $pos2[2]) && ($pos1[2] + 1) < max($pos1[2], $pos2[2])){
            $newPos1[1] = $pos1[2] + 1;
        }
        $newPos2 = [];

        if (($pos2[0] - 1) > min($pos1[0], $pos2[0]) && ($pos2[1] - 1) < max($pos1[0], $pos2[0])){
            $newPos2 = [$pos2[0] - 1];
        }elseif (($pos2[0] + 1) > min($pos1[0], $pos2[0]) && ($pos2[1] + 1) < max($pos1[0], $pos2[0])){
            $newPos2 = [$pos2[0] + 1];
        }
        if (($pos2[2] - 1) > min($pos1[2], $pos2[2]) && ($pos2[2] - 1) < max($pos1[2], $pos2[2])){
            $newPos2[1] = $pos2[2] - 1;
        }elseif (($pos2[2] + 1) > min($pos1[2], $pos2[2]) && ($pos2[2] + 1) < max($pos1[2], $pos2[2])){
            $newPos2[1] = $pos2[2] + 1;
        }
        return [$newPos1, $newPos2];
    }

    /**
     * @param array $pos1
     * @param array $pos2
     * @return array[]
     */
    public static function getAreaCorners(array $pos1, array $pos2): array{
        $corners = [
            [$pos1[0], $pos1[2]],
            [$pos2[0], $pos2[2]]
        ];
        $minX = min($pos1[0], $pos2[0]);
        $maxX = max($pos1[0], $pos2[0]);
        $minZ = min($pos1[2], $pos2[2]);
        $maxZ = max($pos1[2], $pos2[2]);

        if ($pos1[0] > $pos2[0] && $pos1[2] > $pos2[2] || $pos1[0] < $pos2[0] && $pos1[2] < $pos2[2]){
            // Corner 3
            $corners[] = [$maxX, $minZ];
            // Corner 4
            $corners[] = [$minX, $maxZ];
        }elseif ($pos1[0] > $pos2[0] && $pos1[2] < $pos2[2] || $pos1[0] < $pos2[0] && $pos1[2] > $pos2[2]){
            // Corner 3
            $corners[] = [$maxX, $maxZ];
            // Corner 4
            $corners[] = [$minX, $minZ];
        }
        return $corners;
    }

    /**
     * @param array $pos1
     * @param array $pos2
     * @param int $x
     * @param int $z
     * @param Block $block
     * @return Block
     */
    public static function calculateFacingBlock(array $pos1, array $pos2, int $x, int $z, Block $block): Block{
        $minX = min($pos1[0], $pos2[0]);
        $maxX = max($pos1[0], $pos2[0]);
        $minZ = min($pos1[2], $pos2[2]);
        $maxZ = max($pos1[2], $pos2[2]);

        if (in_array(RotatableTrait::class, class_uses($block::class), true)){
            if ($x == $maxX){
                return $block->setFacing(5);
            }elseif ($x == $minX){
                return $block->setFacing(4);
            }
            if ($z == $maxZ){
                return $block->setFacing(3);
            }elseif ($z == $minZ){
                return $block->setFacing(2);
            }
        }
        return $block;
    }
}
