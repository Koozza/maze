<?php
declare(strict_types=1);

namespace App\ValueObjects;

use Exception;

class Maze
{
    private array $maze = [[]];

    /**
     * Make constructor private
     */
    private function __construct()
    {
    }

    public static function initialize(): self
    {
        return new self;
    }

    public function setTile(Tile $tile): self
    {
        $self = clone $this;
        $self->maze[$tile->getRow()][$tile->getCol()] = $tile;

        return $self;
    }

    public function getMaze(): array
    {
        return $this->maze;
    }

    public function getTileAtCoordinate(Coordinate $coordinate): Tile
    {
        return $this->getTileAtLocation($coordinate->getX(), $coordinate->getY());
    }

    public function getTileAtLocation(int $x, int $y): Tile
    {
        if (true === array_key_exists($x, $this->maze) && true === array_key_exists($y, $this->maze[$x])) {
            return $this->maze[$x][$y];
        }

        throw new Exception('Invalid maze coordinate requested');
    }

    public function draw(): string
    {
        $mazeString = '';
        foreach ($this->getMaze() as $row) {
            foreach ($row as $tile) {
                $mazeString .= $tile->draw();
            }
            $mazeString .= PHP_EOL;
        }

        return $mazeString;
    }
}
