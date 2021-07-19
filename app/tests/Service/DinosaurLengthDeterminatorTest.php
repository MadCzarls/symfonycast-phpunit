<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurLengthDeterminatorTest extends TestCase
{
    /**
     * @dataProvider getSpecLengthTests
     */
    public function testItReturnsCorrectLengthRange(
        string $specification,
        int $expectedMinSize,
        int $expectedMaxSize
    ): void {
        $determinator = new DinosaurLengthDeterminator();
        $actualSize = $determinator->getLengthFromSpecification($specification);

        $this->assertGreaterThanOrEqual($expectedMinSize, $actualSize);
        $this->assertLessThanOrEqual($expectedMaxSize, $actualSize);
    }

    /**
     * @return mixed[][]
     */
    public function getSpecLengthTests(): array
    {
        return [
            // specification, min length, max length
            ['large carnivorous dinosaur', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            'default response' => ['give me all the cookies!!!', 0, Dinosaur::LARGE - 1],
            ['large herbivore', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            ['huge dinosaur', Dinosaur::HUGE, 100],
            ['huge dino', Dinosaur::HUGE, 100],
            ['huge', Dinosaur::HUGE, 100],
            ['OMG', Dinosaur::HUGE, 100],
            ['😱', Dinosaur::HUGE, 100],
        ];
    }
}
