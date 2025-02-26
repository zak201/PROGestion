<?php

namespace App\DTO;

use App\Entity\Vehicule;

class VehiculeDTO
{
    private ?int $id = null;
    private ?string $name = null;
    private ?int $lotId = null;

    public function __construct(
        public readonly string $numeroChassis,
        public readonly string $marque,
        public readonly string $status
    ) {}

    public static function fromEntity(Vehicule $vehicule): self
    {
        return new self(
            $vehicule->getNumeroChassis(),
            $vehicule->getMarque(),
            $vehicule->getStatus()
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLotId(): ?int
    {
        return $this->lotId;
    }

    public function setLotId(int $lotId): self
    {
        $this->lotId = $lotId;
        return $this;
    }
} 