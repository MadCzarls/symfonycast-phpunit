<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class EnclosureBuilderProphecyTest extends TestCase
{
    public function testItBuildsAndPersistsEnclosure(): void
    {
        $em = $this->prophesize(EntityManagerInterface::class);
        $em->persist(Argument::type(Enclosure::class))->shouldBeCalledTimes(1);
        $em->flush()->shouldBeCalled();

        $dinoFactory = $this->prophesize(DinosaurFactory::class);
        $dinoFactory
            ->growFromSpecification(Argument::type('string'))
            ->shouldBeCalledTimes(2)
            ->willReturn(new Dinosaur());

        $builder = new EnclosureBuilder($em->reveal(), $dinoFactory->reveal());
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}