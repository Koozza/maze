<?php
declare(strict_types=1);

namespace App\ValueObjects;

use Exception;

class Tile
{
    public const TYPE_SOLID = 'TYPE_SOLID';
    public const TYPE_WALKABLE = 'TYPE_WALKABLE';
    public const TYPE_START = 'TYPE_START';
    public const TYPE_END = 'TYPE_END';
    public const TYPE_CAR = 'TYPE_CAR';

    private string $type;
    private Coordinate $coordinate;
    private bool $shouldSkip = false;

    /**
     * Make constructor private
     */
    private function __construct(string $type, Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;
        $this->type = $type;
    }

    /**
     * @throws Exception
     */
    public static function fromCharacter(string $mazeCharacter, Coordinate $coordinate): self
    {
        $type = self::determineType($mazeCharacter);
        return new self($type, $coordinate);
    }

    public static function fromType(string $type, Coordinate $coordinate): self
    {
        return new self($type, $coordinate);
    }

    /**
     * Call this method if we don't need to look at this tile.
     *
     * @return $this
     */
    public function skipTile(): self
    {
        $self = clone $this;
        $self->shouldSkip = true;

        return $self;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRow(): int
    {
        return $this->coordinate->getX();
    }

    public function getCol(): int
    {
        return $this->coordinate->getY();
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function shouldSkip(): bool
    {
        return $this->shouldSkip;
    }

    public function isSolid(): bool
    {
        return $this->type === self::TYPE_SOLID;
    }

    public function isWalkable(): bool
    {
        return $this->type === self::TYPE_WALKABLE;
    }

    public function isEnd(): bool
    {
        return $this->type === self::TYPE_END;
    }

    public function isStart(): bool
    {
        return $this->type === self::TYPE_START;
    }

    public function isCar(): bool
    {
        return $this->type === self::TYPE_CAR;
    }

    /**
     * @throws Exception
     */
    private static function determineType(string $mazeCharacter): string
    {
        switch (strtoupper($mazeCharacter)) {
            case 'X':
            case 'A':
                return self::TYPE_SOLID;
            case ' ':
                return self::TYPE_WALKABLE;
            case 'J':
                return self::TYPE_START;
            case 'B':
                return self::TYPE_CAR;
        }

        throw new Exception('Invalid maze character found: ' . $mazeCharacter);
    }

    public function draw(): string
    {
        if(true === $this->shouldSkip()) {
            return '-';
        }

        switch ($this->type) {
            case self::TYPE_SOLID:
                return 'X';
            case self::TYPE_WALKABLE:
                return ' ';
            case self::TYPE_CAR:
                return 'B';
            case self::TYPE_END:
                return 'E';
            case self::TYPE_START:
                return 'S';
        }

        return '';
    }
}
