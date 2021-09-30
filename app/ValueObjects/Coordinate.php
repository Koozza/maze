<?php
declare(strict_types=1);

namespace App\ValueObjects;

class Coordinate
{
    private int $x;
    private int $y;

    private function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function initialize(int $x, int $y): self
    {
        return new self($x, $y);
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
