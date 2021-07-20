<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EnclosureBuilderTest extends TestCase
{
    public function testItBuildsAndPersistsEnclosures(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $dinoFactory = $this->createMock(DinosaurFactory::class);

        $dinoFactory
            ->expects($this->exactly(2))
            ->method('growFromSpecification')
            ->with($this->isType('string'))
            ->willReturn(new Dinosaur());

        $em
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Enclosure::class));

        $em
            ->expects($this->atLeastOnce())
            ->method('flush');

        $builder = new EnclosureBuilder($em, $dinoFactory);
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}
