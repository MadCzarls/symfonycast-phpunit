<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use function sprintf;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinosaur")
 */
class Dinosaur
{
    public const LARGE = 10;
    public const HUGE = 30;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="integer") */
    private int $length = 0;

    /** @ORM\Column(type="string") */
    private string $genus;

    /** @ORM\Column(type="boolean") */
    private bool $isCarnivorous;

    /** @ORM\ManyToOne(targetEntity="Enclosure", inversedBy="dinosaurs") */
    private ?Enclosure $enclosure = null;

    public function __construct(string $genus = 'Unknown', bool $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function getSpecification(): string
    {
        return sprintf(
            'The %s %scarnivorous dinosaur is %d meters long',
            $this->genus,
            $this->isCarnivorous ? '' : 'non-',
            $this->length
        );
    }

    public function getGenus(): string
    {
        return $this->genus;
    }

    public function isCarnivorous(): bool
    {
        return $this->isCarnivorous;
    }

    public function setEnclosure(Enclosure $enclosure): void
    {
        $this->enclosure = $enclosure;
    }
}
