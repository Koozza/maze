<?php
declare(strict_types=1);

namespace App\Services;

use App\ValueObjects\Coordinate;
use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class DeadEndFinderService
{
    private LocationService $locationService;

    public function __construct()
    {
        $this->locationService = new LocationService();
    }

    /**
     * @throws Exception
     */
    public function findDeadEnd(Maze $maze): ?Tile
    {
        $mazeTiles = $maze->getMaze();
        foreach ($mazeTiles as $row) {
            /** @var Tile $tile */
            foreach ($row as $tile) {
                //Find a walkable tile, check the 4 surrounding tiles for obstructions
                if (false === $tile->shouldSkip() && true === $tile->isWalkable() && $this->isDeadEnd($maze, $tile)) {
                    return $tile;
                }
            }
        }

        return null;
    }

    private function isDeadEnd(Maze $maze, Tile $tile): bool
    {
        return count(array_filter([
            $this->isUpperTileSolid($maze, $tile),
            $this->isLowerTileSolid($maze, $tile),
            $this->isLeftTileSolid($maze, $tile),
            $this->isRightTileSolid($maze, $tile),
        ])) === 3;
    }

    private function isUpperTileSolid(Maze $maze, Tile $tile): bool
    {
        return $this->isNeighbourTileSolid($maze, $tile, LocationService::DIR_UP);
    }

    private function isLowerTileSolid(Maze $maze, Tile $tile): bool
    {
        return $this->isNeighbourTileSolid($maze, $tile, LocationService::DIR_DOWN);
    }

    private function isLeftTileSolid(Maze $maze, Tile $tile): bool
    {
        return $this->isNeighbourTileSolid($maze, $tile, LocationService::DIR_LEFT);
    }

    private function isRightTileSolid(Maze $maze, Tile $tile): bool
    {
        return $this->isNeighbourTileSolid($maze, $tile, LocationService::DIR_RIGHT);
    }

    private function isNeighbourTileSolid(Maze $maze, Tile $tile, string $direction): bool
    {
        $neighbourTile = $this->locationService->getNeighbourTileForDirection($maze, $tile, $direction);
        if (null === $neighbourTile) {
            return false;
        }

        return $neighbourTile->isSolid() || $neighbourTile->isEnd();
    }
}
