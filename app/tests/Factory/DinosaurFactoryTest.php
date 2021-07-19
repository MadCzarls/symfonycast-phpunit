<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Dinosaur;
use App\Factory\DinosaurFactory;
use PHPUnit\Framework\TestCase;

use function is_string;

class DinosaurFactoryTest extends TestCase
{
    public function testItGrowsALargeVelociraptor(): void
    {
        $factory = new DinosaurFactory();
        $dinosaur = $factory->growVelociraptor(5);
        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertTrue(is_string($dinosaur->getGenus()));
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }
}
