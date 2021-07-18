<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testSettingLength(): void
    {
        $dinosaur = new Dinosaur();
        $this->assertSame(0, $dinosaur->getLength());

        $dinosaur->setLength(9);
        $this->assertSame(9, $dinosaur->getLength());
    }

    public function testDinosaurHasNotShrunk(): void
    {
        $dinosaur = new Dinosaur();
        $dinosaur->setLength(15);

        $this->assertGreaterThan(12, $dinosaur->getLength(), 'Did you put it in washing machine?');
    }

    public function testReturnsFullSpecificationOfDinosaur(): void
    {
        $dinosaur = new Dinosaur();
        $this->assertSame('The Unknown non-carnivorous dinosaur is 0 meters long', $dinosaur->getSpecification());
    }

    public function testReturnsFullSpecificationForTyrannosaurus(): void
    {
        $dinosaur = new Dinosaur('Tyrannosarus', true);
        $dinosaur->setLength(12);
        $this->assertSame('The Tyrannosarus carnivorous dinosaur is 12 meters long', $dinosaur->getSpecification());
    }
}
