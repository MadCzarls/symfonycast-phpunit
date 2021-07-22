<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Enclosure;
use App\Entity\Security;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadSecurityData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $herbivorousEnclosure = $this->getReference('herbivorous-enclosure');
        $this->addSecurity($herbivorousEnclosure, 'Fence', true);
        $carnivorousEnclosure = $this->getReference('carnivorous-enclosure');
        $this->addSecurity($carnivorousEnclosure, 'Electric fence', false);
        $this->addSecurity($carnivorousEnclosure, 'Guard tower', false);
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [LoadBasicParkData::class];
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['basic'];
    }

    private function addSecurity(
        Enclosure $enclosure,
        string $name,
        bool $isActive
    ): void {
        $enclosure->addSecurity(new Security($name, $isActive, $enclosure));
    }
}
