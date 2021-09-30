<?php
declare(strict_types=1);

namespace App\Services;

use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class LocationService
{
    public const DIR_LEFT = 'LEFT';
    public const DIR_RIGHT = 'RIGHT';
    public const DIR_UP = 'UP';
    public const DIR_DOWN = 'DOWN';

    /**
     * @throws Exception
     */
    public function getNeighbourTileForDirection(Maze $maze, Tile $tile, string $direction): ?Tile
    {
        switch ($direction) {
            case self::DIR_UP:
                return $this->getTileAtLocation($maze, $tile->getRow() - 1, $tile->getCol());
            case self::DIR_DOWN:
                return $this->getTileAtLocation($maze, $tile->getRow() + 1, $tile->getCol());
            case self::DIR_RIGHT:
                return $this->getTileAtLocation($maze, $tile->getRow(), $tile->getCol() - 1);
            case self::DIR_LEFT:
                return $this->getTileAtLocation($maze, $tile->getRow(), $tile->getCol() + 1);
        }

        throw new Exception('Invalid direction');
    }

    public function findStartLocation(Maze $maze): Tile
    {
        foreach ($maze->getMaze() as $row) {
            /** @var Tile $tile */
            foreach ($row as $tile) {
                if (true === $tile->isStart()) {
                    return $tile;
                }
            }
        }

        throw new Exception('Start not found');
    }

    private function getTileAtLocation(Maze $maze, int $x, int $y): ?Tile
    {
        try {
            return $maze->getTileAtLocation($x, $y);
        } catch (Exception $e) {
            return null;
        }
    }
}
