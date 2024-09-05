<?php

namespace byalperens\border\async;

use byalperens\border\blocks\WorldBorderBlock;
use byalperens\border\math\AreaMath;
use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\VanillaBlocks;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;
use pocketmine\world\World;

class BorderAsync extends AsyncTask{

    private string $pos1;
    private string $pos2;
    private string $chunks;

    /**
     * @param string $world
     * @param array $pos1
     * @param array $pos2
     * @param array $chunks
     * @param string $type
     */
    public function __construct(
        private string $world,
        array $pos1,
        array $pos2,
        array $chunks,
        private string $type
    ){
        $this->pos1 = serialize($pos1);
        $this->pos2 = serialize($pos2);
        $this->chunks = serialize($chunks);
    }

    public function onRun(): void{
        $pos1 = unserialize($this->pos1, [""]);
        $pos2 = unserialize($this->pos2, [""]);
        $chunks = unserialize($this->chunks, [""]);

        if ($this->type == "create"){
            /** @var WorldBorderBlock $block */
            $block = CustomiesBlockFactory::getInstance()->get("custom:world_border");
        }elseif ($this->type == "delete"){
            $block = VanillaBlocks::AIR();
        }else{
            $block = VanillaBlocks::AIR();
        }
        foreach ($chunks as $hash => $binary){
            $chunks[$hash] = FastChunkSerializer::deserializeTerrain($binary);
        }
        for ($xx = min($pos1[0], $pos2[0]); $xx <= max($pos1[0], $pos2[0]); $xx++){
            for ($zz = min($pos1[2], $pos2[2]); $zz <= max($pos1[2], $pos2[2]); $zz++){
                $chunk = $chunks[World::chunkHash($xx >> Chunk::COORD_BIT_SIZE, $zz >> Chunk::COORD_BIT_SIZE)];
                $calculateHallow = AreaMath::calculateHallowArea($pos1, $pos2);
                $newPos1 = $calculateHallow[0];
                $newPos2 = $calculateHallow[1];

                if ($xx >= min($newPos1[0], $newPos2[0]) && $xx <= max($newPos1[0], $newPos2[0]) && $zz >= min($newPos1[1], $newPos2[1]) && $zz <= max($newPos1[1], $newPos2[1])){
                    continue;
                }
                if (in_array([$xx, $zz], AreaMath::getAreaCorners($pos1, $pos2))){
                    continue;
                }
                for ($y = ($pos1[1] + 1); $y <= 256; ++$y){
                    $chunk->setBlockStateId($xx & Chunk::COORD_MASK, $y, $zz & Chunk::COORD_MASK, AreaMath::calculateFacingBlock($pos1, $pos2, $xx, $zz, $block)->getStateId());
                }
            }
        }
        $this->setResult($chunks);
    }

    public function onCompletion(): void{
        $world = Server::getInstance()->getWorldManager()->getWorldByName($this->world);
        $chunks = $this->getResult();

        foreach ($chunks as $hash => $chunk){
            World::getXZ($hash, $x, $z);
            $world->setChunk($x, $z, $chunk);
        }
    }
}
