<?php
declare(strict_types=1);

namespace App\Services;

use App\ValueObjects\Coordinate;
use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class ExitFinderService
{
    /**
     * @throws Exception
     */
    public function findExitCoordinate(Maze $maze): Coordinate
    {
        $options = [
            $this->checkTopEdge($maze),
            $this->checkBottomEdge($maze),
            $this->checkLeftEdge($maze),
            $this->checkRightEdge($maze),
        ];

        $result = array_values(array_filter($options));
        $exitCoordinate = count($result) > 0 ? $result[0] : null;

        if (null === $exitCoordinate) {
            throw new Exception('Exit not found');
        }

        return $exitCoordinate;
    }

    private function checkTopEdge(Maze $maze): ?Coordinate
    {
        return $this->checkRowForExit($maze, 0);
    }

    private function checkBottomEdge(Maze $maze): ?Coordinate
    {
        $rowCount = count($maze->getMaze()) - 1;
        return $this->checkRowForExit($maze, $rowCount);
    }

    private function checkLeftEdge(Maze $maze): ?Coordinate
    {
        return $this->checkColForExit($maze, 0);
    }

    private function checkRightEdge(Maze $maze): ?Coordinate
    {
        $colCount = count($maze->getMaze()[0]) - 1;
        return $this->checkColForExit($maze, $colCount);
    }

    private function checkRowForExit(Maze $maze, int $row): ?Coordinate
    {
        $tiles = $maze->getMaze();

        /**
         * @var int $col
         * @var Tile $tile
         */
        foreach ($tiles[$row] as $col => $tile) {
            if ($tile->getType() === Tile::TYPE_WALKABLE) {
                return Coordinate::initialize($row, $col);
            }
        }

        return null;
    }

    private function checkColForExit(Maze $maze, int $col): ?Coordinate
    {
        $tiles = $maze->getMaze();

        /**
         * @var array $row
         * @var int $col
         */
        foreach ($tiles as $rowIndex => $row) {
            if ($row[$col]->getType() === Tile::TYPE_WALKABLE) {
                return Coordinate::initialize($rowIndex, $col);
            }
        }

        return null;
    }
}
