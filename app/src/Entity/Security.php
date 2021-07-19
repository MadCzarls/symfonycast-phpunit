<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="security")
 */
class Security
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
    /** @ORM\Column(type="string") */
    private string $name;
    /** @ORM\Column(type="boolean") */
    private bool $isActive;
    /** @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="securities") */
    private Enclosure $enclosure;

    public function __construct(string $name, bool $isActive, Enclosure $enclosure)
    {
        $this->name = $name;
        $this->isActive = $isActive;
        $this->enclosure = $enclosure;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}
