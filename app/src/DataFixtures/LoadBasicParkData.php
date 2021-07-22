<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LoadBasicParkData extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $carnivorousEnclosure = new Enclosure();
        $manager->persist($carnivorousEnclosure);
        $this->addReference('carnivorous-enclosure', $carnivorousEnclosure);
        $herbivorousEnclosure = new Enclosure();
        $manager->persist($herbivorousEnclosure);
        $this->addReference('herbivorous-enclosure', $herbivorousEnclosure);
        $manager->persist(new Enclosure(true));
        $this->addDinosaur($manager, $carnivorousEnclosure, 'Velociraptor', true, 3);
        $this->addDinosaur($manager, $carnivorousEnclosure, 'Velociraptor', true, 1);
        $this->addDinosaur($manager, $carnivorousEnclosure, 'Velociraptor', true, 5);
        $this->addDinosaur($manager, $herbivorousEnclosure, 'Triceratops', false, 7);
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['basic'];
    }

    private function addDinosaur(ObjectManager $manager, Enclosure $enclosure, string $genus, bool $isCarnivorous, int $length): void
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setEnclosure($enclosure);
        $dinosaur->setLength($length);
        $manager->persist($dinosaur);
    }
}
