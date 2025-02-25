<?php

namespace App\Entity;

use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['numero_lot'],
    message: 'Ce numéro de lot existe déjà.',
    groups: ['creation']
)]
class Lot
{
    public const STATUS_EN_ATTENTE = 'en_attente';
    public const STATUS_EN_COURS = 'en_cours';
    public const STATUS_TERMINE = 'termine';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le numéro de lot est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le numéro de lot doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le numéro de lot ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $numero_lot = null;

    #[ORM\Column(length: 255)]
    private string $statut = self::STATUS_EN_ATTENTE;

    /** @var Collection<int, Vehicule> */
    #[ORM\OneToMany(mappedBy: 'lot', targetEntity: Vehicule::class, cascade: ['persist'])]
    #[Assert\Count(
        max: 10,
        maxMessage: 'Un lot ne peut pas contenir plus de {{ limit }} véhicules'
    )]
    private Collection $vehicules;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateCloture = null;

    #[ORM\ManyToOne(targetEntity: Camion::class, inversedBy: 'lots')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Camion $camion = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateExpedition = null;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
        $this->statut = self::STATUS_EN_ATTENTE;
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

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
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

    public function getCamion(): ?Camion
    {
        return $this->camion;
    }

    public function setCamion(?Camion $camion): self
    {
        if ($this->camion !== null && $this->camion !== $camion) {
            $this->camion->removeLot($this);
        }
        
        $this->camion = $camion;
        
        if ($camion !== null && !$camion->getLots()->contains($this)) {
            $camion->addLot($this);
        }

        return $this;
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

    public function getDateExpedition(): ?\DateTimeInterface
    {
        return $this->dateExpedition;
    }

    public function setDateExpedition(\DateTimeInterface $dateExpedition): self
    {
        $this->dateExpedition = $dateExpedition;
        return $this;
    }

    public function getStatutLabel(): string
    {
        return match ($this->statut) {
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_EN_COURS => 'En cours',
            self::STATUS_TERMINE => 'Terminé',
            default => 'Inconnu'
        };
    }
}
