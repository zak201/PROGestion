<?php

namespace App\Entity;

use App\Repository\AvarieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvarieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Avarie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'numéro_chassis')]
    private ?string $numeroChassis = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $responsabilite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'date_signalement')]
    private ?\DateTimeInterface $dateSignalement = null;

    #[ORM\Column(length: 255)]
    private ?string $traitement = null;

    #[ORM\Column]
    private ?bool $bloquage = null;

    #[ORM\Column(length: 255, name: 'zone_stock')]
    private ?string $zoneStock = null;

    #[ORM\Column(length: 255, name: 'lien_compound', nullable: true)]
    private ?string $lienCompound = null;

    #[ORM\Column(name: 'dossier_cloture')]
    private ?bool $dossierCloture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'date_creation')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Vehicule::class)]
    #[ORM\JoinColumn(name: 'vehicule_id', referencedColumnName: 'id')]
    private ?Vehicule $vehicule = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Vehicule::class)]
    private ?Vehicule $vehicule = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroChassis(): ?string
    {
        return $this->numeroChassis;
    }

    public function setNumeroChassis(string $numeroChassis): static
    {
        $this->numeroChassis = $numeroChassis;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getResponsabilite(): ?string
    {
        return $this->responsabilite;
    }

    public function setResponsabilite(string $responsabilite): static
    {
        $this->responsabilite = $responsabilite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateSignalement(): ?\DateTimeInterface
    {
        return $this->dateSignalement;
    }

    public function setDateSignalement(\DateTimeInterface $dateSignalement): static
    {
        $this->dateSignalement = $dateSignalement;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function isBloquage(): ?bool
    {
        return $this->bloquage;
    }

    public function setBloquage(bool $bloquage): static
    {
        $this->bloquage = $bloquage;

        return $this;
    }

    public function getZoneStock(): ?string
    {
        return $this->zoneStock;
    }

    public function setZoneStock(string $zoneStock): static
    {
        $this->zoneStock = $zoneStock;

        return $this;
    }

    public function getLienCompound(): ?string
    {
        return $this->lienCompound;
    }

    public function setLienCompound(?string $lienCompound): static
    {
        $this->lienCompound = $lienCompound;

        return $this;
    }

    public function isDossierCloture(): ?bool
    {
        return $this->dossierCloture;
    }

    public function setDossierCloture(bool $dossierCloture): static
    {
        $this->dossierCloture = $dossierCloture;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    #[ORM\PrePersist]
    public function setDateCreationValue(): void
    {
        $this->dateCreation = new \DateTimeImmutable();
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;
        return $this;
    }
}
