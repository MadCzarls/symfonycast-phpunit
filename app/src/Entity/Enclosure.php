<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\DinosaursAreRunningRampantException;
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
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToMany(targetEntity="Dinosaur", mappedBy="enclosure", cascade={"persist"})
     *
     * @var Collection<int, Dinosaur>
     */
    private Collection $dinosaurs;

    /**
     * @ORM\OneToMany(targetEntity="Security", mappedBy="enclosure", cascade={"persist"})
     *
     * @var Collection<int, Security>
     */
    private Collection $securities;

    public function __construct(bool $withBasicSecurity = false)
    {
        $this->dinosaurs = new ArrayCollection();
        $this->securities = new ArrayCollection();

        if (!$withBasicSecurity) {
            return;
        }

        $this->addSecurity(new Security('Fence', true, $this));
    }

    public function addSecurity(Security $security): void
    {
        $this->securities->add($security);
    }

    public function addDinosaur(Dinosaur $dinosaur): void
    {
        if (!$this->isSecurityActive()) {
            throw new DinosaursAreRunningRampantException('Are you craaazy?!?');
        }

        if (!$this->canAddDinosaur($dinosaur)) {
            throw new NotABuffetException();
        }

        $this->dinosaurs->add($dinosaur);
    }

    public function getDinosaurs(): ArrayCollection|Collection
    {
        return $this->dinosaurs;
    }

    public function getSecurities(): ArrayCollection|Collection
    {
        return $this->securities;
    }

    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            if ($security->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    private function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        return count($this->dinosaurs) === 0 ||
            $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }
}
