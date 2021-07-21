<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Security;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderIntegrationTest extends KernelTestCase
{
    public function testItBuildsEnclosureWithDefaultSpecifications(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        /** @var EnclosureBuilder $enclosureBuilder */
        $enclosureBuilder = $container->get(EnclosureBuilder::class);
        $enclosureBuilder->buildEnclosure();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);

        /** @var EntityRepository $securityRepository */
        $securityRepository = $entityManager->getRepository(Security::class);
        $securityCount = $securityRepository->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $securityCount, 'Amount of security systems is not the same');

        /** @var EntityRepository $dinosaurRepository */
        $dinosaurRepository = $entityManager->getRepository(Dinosaur::class);
        $dinosaurCount = $dinosaurRepository->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $dinosaurCount, 'Amount of dinosaurs is not the same');
    }
}
