<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Dinosaur;
use App\Factory\DinosaurFactory;
use App\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

use function class_exists;
use function is_string;

class DinosaurFactoryTest extends TestCase
{
    private DinosaurFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $lengthDeterminator = $this->createStub(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($lengthDeterminator);
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
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby!');
        }

        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());
    }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromSpecification(
        string $specification,
        bool $expectedIsCarnivorous
    ): void {
        $dinosaur = $this->factory->growFromSpecification($specification);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Dietes do not match');
    }

    /**
     * @return mixed[][]
     */
    public function getSpecificationTests(): array
    {
        //specification, is carnivorous
        return [
            ['large carnivorous dinosaur', true],
            'default response' => ['give me all the cookies', false],
            ['large herbivore', false],
        ];
    }
}
