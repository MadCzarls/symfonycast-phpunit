<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Entity\Security;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $this->truncateEntities(
            [
                Enclosure::class,
                Security::class,
                Dinosaur::class,
            ]
        );
    }

    public function testItBuildsEnclosureWithDefaultSpecifications_PARTIAL_MOCKING(): void
    {
        $entityManager = $this->getEntityManager();
        $dinoFactory = $this->createMock(DinosaurFactory::class);
        $dinoFactory
            ->expects($this->any())
            ->method('growFromSpecification')
            ->willReturnCallback(static function ($spec) {
                return new Dinosaur();
            });

        $enclosureBuilder = new EnclosureBuilder($entityManager, $dinoFactory);
        $enclosureBuilder->buildEnclosure();

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


    public function testItBuildsEnclosureWithDefaultSpecifications(): void
    {
        $container = static::getContainer();
        /** @var EnclosureBuilder $enclosureBuilder */
        $enclosureBuilder = $container->get(EnclosureBuilder::class);
        $enclosureBuilder->buildEnclosure();

        $entityManager = $this->getEntityManager();

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

    /**
     * @param string[] $entities
     */
    private function truncateEntities(array $entities): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery("SET session_replication_role = 'replica';"); //postgresql specific query
        }

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName(),
                true
            );
            $connection->executeStatement($query);
        }

        if (!$databasePlatform->supportsForeignKeyConstraints()) {
            return;
        }

        $connection->executeQuery("SET session_replication_role = 'origin';"); //postgresql specific query
    }

    private function getEntityManager(): EntityManagerInterface
    {
        $container = static::getContainer();

        return $container->get(EntityManagerInterface::class);
    }
}
