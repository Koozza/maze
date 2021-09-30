<?php
declare(strict_types=1);

namespace App\Services;

use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class MazeSolverService
{
    private ExitFinderService $exitFinderService;
    private DeadEndFinderService $deadEndFinderService;
    private NeighbourFinderService $neighbourFinderService;
    private LocationService $locationService;

    public function __construct()
    {
        $this->exitFinderService = new ExitFinderService();
        $this->deadEndFinderService = new DeadEndFinderService();
        $this->neighbourFinderService = new NeighbourFinderService();
        $this->locationService = new LocationService();
    }

    public function solve(Maze $maze): string
    {
        $maze = $this->findAndSetExit($maze);
        while ($deadEndTile = $this->deadEndFinderService->findDeadEnd($maze)) {
            $maze = $this->invalidateDeadPaths($maze, $deadEndTile);
        }

        //First do the path to the van
        $startTile = $this->locationService->findStartLocation($maze);
        $answer = $this->walkPath($maze, $startTile);

        print_r($maze->draw());

        return '';
    }

    private function walkPath(Maze $maze, Tile $start): string
    {
        //TODO: Uitwerken
        while ($nextTile = $this->neighbourFinderService->findWalkableNeighbour($maze, $start)) {
            //print_r($this->neighbourFinderService->getWalkableNeighbourDirection($start, $nextTile));
            //exit();
        }

        return '';
    }

    private function invalidateDeadPaths(Maze $maze, Tile $deadEndTile): Maze
    {
        //Skip the dead end tile
        if(false === $deadEndTile->isEnd()) {
            $deadEndTile = $deadEndTile->skipTile();
            $maze = $maze->setTile($deadEndTile);
        }

        while (null !== ($deadEndTile = $this->neighbourFinderService->findWalkableNeighbour($maze, $deadEndTile))) {
            $deadEndTile = $deadEndTile->skipTile();
            $maze = $maze->setTile($deadEndTile);
        }

        return $maze;
    }

    /**
     * @throws Exception
     */
    private function findAndSetExit(Maze $maze): Maze
    {
        $exitCoordinate = $this->exitFinderService->findExitCoordinate($maze);
        $exitTile = Tile::fromType(Tile::TYPE_END, $exitCoordinate);
        return $maze->setTile($exitTile);
    }
}
