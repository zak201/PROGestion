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

    #[ORM\Column(length: 255)]
    private ?string $numéro_chassis = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $responsabilite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_signalement = null;

    #[ORM\Column(length: 255)]
    private ?string $traitement = null;

    #[ORM\Column]
    private ?bool $bloquage = null;

    #[ORM\Column(length: 255)]
    private ?string $zone_stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lien_compound = null;

    #[ORM\Column]
    private ?bool $dossier_cloture = null;

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

    public function getNuméroChassis(): ?string
    {
        return $this->numéro_chassis;
    }

    public function setNuméroChassis(string $numéro_chassis): static
    {
        $this->numéro_chassis = $numéro_chassis;

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
        return $this->date_signalement;
    }

    public function setDateSignalement(\DateTimeInterface $date_signalement): static
    {
        $this->date_signalement = $date_signalement;

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
        return $this->zone_stock;
    }

    public function setZoneStock(string $zone_stock): static
    {
        $this->zone_stock = $zone_stock;

        return $this;
    }

    public function getLienCompound(): ?string
    {
        return $this->lien_compound;
    }

    public function setLienCompound(?string $lien_compound): static
    {
        $this->lien_compound = $lien_compound;

        return $this;
    }

    public function isDossierCloture(): ?bool
    {
        return $this->dossier_cloture;
    }

    public function setDossierCloture(bool $dossier_cloture): static
    {
        $this->dossier_cloture = $dossier_cloture;

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
