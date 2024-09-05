<?php

namespace byalperens\border\area\operation;

use byalperens\border\async\BorderAsync;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;
use pocketmine\world\World;

abstract class BorderOperation{

    /**
     * @return array
     */
    private function getChunks(): array{
        $world = $this->getWorld();
        $pos1 = $this->getPos1()->floor();
        $pos2 = $this->getPos2()->floor();
        $chunks = [];

        for ($x = min($pos1->getX(), $pos2->getX()); $x <= max($pos1->getX(), $pos2->getX()); $x++){
            for ($z = min($pos1->getZ(), $pos2->getZ()); $z <= max($pos1->getZ(), $pos2->getZ()); $z++){
                $chunks[World::chunkHash($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE)] = FastChunkSerializer::serializeTerrain($world->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE));
            }
        }
        return $chunks;
    }

    public function build(): void{
        Server::getInstance()->getAsyncPool()->submitTask(new BorderAsync($this->getWorld()->getFolderName(), [$this->getPos1()->getX(), $this->getPos1()->getY(), $this->getPos1()->getZ()], [$this->getPos2()->getX(), $this->getPos2()->getY(), $this->getPos2()->getZ()], $this->getChunks(), "create"));
    }

    public function delete(): void{
        Server::getInstance()->getAsyncPool()->submitTask(new BorderAsync($this->getWorld()->getFolderName(), [$this->getPos1()->getX(), $this->getPos1()->getY(), $this->getPos1()->getZ()], [$this->getPos2()->getX(), $this->getPos2()->getY(), $this->getPos2()->getZ()], $this->getChunks(), "delete"));
    }

    abstract public function getWorld(): World;
    abstract public function getPos1(): Vector3;
    abstract public function getPos2(): Vector3;
}
