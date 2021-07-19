<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function count;

/**
 * @ORM\Entity
 * @ORM\Table(name="enclosure")
 */
class Enclosure
{
    /**
     * @ORM\OneToMany(targetEntity="Dinosaur", mappedBy="enclosure", cascade={"persist"})
     *
     * @var Collection<int, Dinosaur>
     */
    private Collection $dinosaurs;

    public function __construct()
    {
        $this->dinosaurs = new ArrayCollection();
    }

    public function addDinosaur(Dinosaur $dinosaur): void
    {
        if (!$this->canAddDinosaur($dinosaur)) {
            throw new NotABuffetException();
        }

        $this->dinosaurs->add($dinosaur);
    }

    public function getDinosaurs(): ArrayCollection|Collection
    {
        return $this->dinosaurs;
    }

    private function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        return count($this->dinosaurs) === 0 ||
            $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }
}