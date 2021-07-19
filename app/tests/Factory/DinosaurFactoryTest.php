<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Dinosaur;
use App\Factory\DinosaurFactory;
use PHPUnit\Framework\TestCase;

use function class_exists;
use function is_string;

class DinosaurFactoryTest extends TestCase
{
    private DinosaurFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new DinosaurFactory();
    }

    public function testItGrowsALargeVelociraptor(): void
    {
        $dinosaur = $this->factory->growVelociraptor(5);
        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertTrue(is_string($dinosaur->getGenus()));
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    public function testItGrowsATriceratops(): void
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }

    public function testItGrowsABabyVelociraptor(): void
    {
        if (! class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby!');
        }

        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());
    }
}
