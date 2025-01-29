<?php

namespace App\Entity;

use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_lot = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    /** @var Collection<int, Vehicule> */
    #[ORM\OneToMany(mappedBy: 'lot', targetEntity: Vehicule::class)]
    private Collection $vehicules;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateCloture = null;

    #[ORM\ManyToOne(targetEntity: Camion::class, inversedBy: 'lots')]
    private ?Camion $camion = null;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroLot(): ?string
    {
        return $this->numero_lot;
    }

    public function setNumeroLot(string $numero_lot): static
    {
        $this->numero_lot = $numero_lot;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): self
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setLot($this);
        }
        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): self
    {
        if ($this->vehicules->removeElement($vehicule)) {
            if ($vehicule->getLot() === $this) {
                $vehicule->setLot(null);
            }
        }
        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function getDateCloture(): ?\DateTimeImmutable
    {
        return $this->dateCloture;
    }

    public function setDateCloture(?\DateTimeImmutable $dateCloture): self
    {
        $this->dateCloture = $dateCloture;
        return $this;
    }

    public function getVehiculesCount(): int
    {
        return $this->vehicules->count();
    }

    public function __toString(): string
    {
        return $this->numero_lot ?? '';
    }

    public function setCamion(?Camion $camion): self
    {
        $this->camion = $camion;
        return $this;
    }

    public function getCamion(): ?Camion
    {
        return $this->camion;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero_lot;
    }

    public function setNumero(string $numero): self
    {
        $this->numero_lot = $numero;
        return $this;
    }
}
