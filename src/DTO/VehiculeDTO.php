<?php

namespace App\DTO;

use App\Entity\Vehicule;

class VehiculeDTO
{
    private $id;
    private $name;
    private $lotId;

    public function __construct(
        public readonly string $numeroChassis,
        public readonly string $marque,
        public readonly string $statut
    ) {}

    public static function fromEntity(Vehicule $vehicule): self
    {
        return new self(
            $vehicule->getNumeroChassis(),
            $vehicule->getMarque(),
            $vehicule->getStatut()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLotId()
    {
        return $this->lotId;
    }

    public function setLotId($lotId)
    {
        $this->lotId = $lotId;
    }
} 