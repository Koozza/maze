<?php
declare(strict_types=1);

namespace App;

use App\Services\MazeSolverService;
use App\ValueObjects\Coordinate;
use App\ValueObjects\Maze;
use App\ValueObjects\Tile;
use Exception;

class App
{
    private MazeSolverService $mazeSolverService;

    public function __construct()
    {
        $this->mazeSolverService = new MazeSolverService();
    }

    public function run(): void
    {
        $maze = $this->createMaze();
        $this->mazeSolverService->solve($maze);
    }

    /**
     * @throws Exception
     */
    private function createMaze(): Maze
    {
        $mazeObject = Maze::initialize();
        $mazeString = trim(file_get_contents(__DIR__ . '/../maze.txt'));
        $mazeChars = str_split($mazeString);
        $mazeWidth = strlen(explode(PHP_EOL, $mazeString)[0]) + 1;

        $row = 0;
        foreach ($mazeChars as $col => $char) {
            //If at the end of the line, start new row
            if (PHP_EOL === $char) {
                $row++;
                continue;
            }

            //Compensate col (continuous count with the row offset)
            $rowCompensatedCol = $col - ($mazeWidth * $row);

            //Initialize tile object and add to maze object
            $coordinate = Coordinate::initialize($row, $rowCompensatedCol);
            $tile = Tile::fromCharacter($char, $coordinate);
            $mazeObject = $mazeObject->setTile($tile);
        }

        return $mazeObject;
    }
}
