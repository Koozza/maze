<?php
declare(strict_types=1);

namespace App\Services;

use App\ValueObjects\Coordinate;
use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class NeighbourFinderService
{
    private LocationService $locationService;

    public function __construct()
    {
        $this->locationService = new LocationService();
    }

    /**
     * @throws Exception
     */
    public function findWalkableNeighbour(Maze $maze, Tile $tile): ?Tile
    {
        $walkableTiles = $this->getWalkableTiles($maze, $tile);
        if (count($walkableTiles) > 0) {
            return false === $this->isCrossroads($maze, $walkableTiles[0]) ? $walkableTiles[0] : null;
        }

        return null;
    }

    public function getWalkableNeighbourDirection(Tile $tile, Tile $nextTile)
    {
        if ($tile->getRow() === $nextTile->getRow()) {
            if ($tile->getCol() < $nextTile->getCol()) {
                return LocationService::DIR_LEFT;
            }
            return LocationService::DIR_RIGHT;
        }

        if ($tile->getRow() < $nextTile->getRow()) {
            return LocationService::DIR_UP;
        }
        return LocationService::DIR_DOWN;
    }

    private function getWalkableTiles(Maze $maze, Tile $tile): array
    {
        return array_values(array_filter([
            $this->getUpperWalkableTile($maze, $tile),
            $this->getLowerWalkableTile($maze, $tile),
            $this->getLeftWalkableTile($maze, $tile),
            $this->getRightWalkableTile($maze, $tile),
        ]));
    }

    public function isCrossroads(Maze $maze, Tile $tile): bool
    {
        return count($this->getWalkableTiles($maze, $tile)) >= 2;
    }

    public function isFinish(Maze $maze, Tile $tile): bool
    {
        foreach ($this->getWalkableTiles($maze, $tile) as $tile) {
            if (true === $tile->isEnd()) {
                return true;
            }
        }

        return false;
    }

    private function getUpperWalkableTile(Maze $maze, Tile $tile): ?Tile
    {
        return $this->getNeighbourWalkableTile($maze, $tile, LocationService::DIR_UP);
    }

    private function getLowerWalkableTile(Maze $maze, Tile $tile): ?Tile
    {
        return $this->getNeighbourWalkableTile($maze, $tile, LocationService::DIR_DOWN);
    }

    private function getLeftWalkableTile(Maze $maze, Tile $tile): ?Tile
    {
        return $this->getNeighbourWalkableTile($maze, $tile, LocationService::DIR_LEFT);
    }

    private function getRightWalkableTile(Maze $maze, Tile $tile): ?Tile
    {
        return $this->getNeighbourWalkableTile($maze, $tile, LocationService::DIR_RIGHT);
    }

    private function getNeighbourWalkableTile(Maze $maze, Tile $tile, string $direction): ?Tile
    {
        $neighbourTile = $this->locationService->getNeighbourTileForDirection($maze, $tile, $direction);

        return null !== $neighbourTile && true === $this->isWalkableTile($neighbourTile) ? $neighbourTile : null;
    }

    private function isWalkableTile(Tile $tile): bool
    {
        return (true === $tile->isWalkable() || $tile->isStart()) && false === $tile->shouldSkip();
    }
}
