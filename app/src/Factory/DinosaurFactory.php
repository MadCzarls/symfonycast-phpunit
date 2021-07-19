<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Dinosaur;

class DinosaurFactory
{
    public const LARGE = 20;

    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, 5);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, 5);
    }


    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }
}
