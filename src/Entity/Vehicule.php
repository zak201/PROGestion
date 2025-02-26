<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 17, unique: true)]
    #[Assert\NotBlank(message: 'Le numéro de chassis est obligatoire')]
    #[Assert\Length(
        min: 17,
        max: 17,
        exactMessage: 'Le numéro de chassis doit contenir exactement 17 caractères'
    )]
    private ?string $numeroChassis = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La marque est obligatoire')]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(['disponible', 'bloque', 'en_maintenance'])]
    private string $status = 'disponible';

    #[ORM\ManyToOne(targetEntity: Lot::class, inversedBy: 'vehicules')]
    private ?Lot $lot = null;

    #[ORM\ManyToOne(targetEntity: Navire::class, inversedBy: 'vehicules')]
    private ?Navire $navire = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroChassis(): ?string
    {
        return $this->numeroChassis;
    }

    public function setNumeroChassis(string $numeroChassis): self
    {
        $this->numeroChassis = strtoupper($numeroChassis);
        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;
        return $this;
    }

    public function getNavire(): ?Navire
    {
        return $this->navire;
    }

    public function setNavire(?Navire $navire): self
    {
        $this->navire = $navire;
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'disponible';
    }

    public function canBeAddedToLot(): bool
    {
        return $this->isAvailable() && $this->lot === null;
    }

    public function __toString(): string
    {
        return $this->numeroChassis;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
