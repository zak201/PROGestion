<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LotFormData
{
    #[Assert\NotBlank(message: 'Le numéro de lot est obligatoire')]
    public ?string $numeroLot = null;

    #[Assert\NotBlank(message: 'Les numéros de véhicules sont obligatoires')]
    public ?string $vehiculesInput = null;

    public ?string $camionInput = null;

    public ?\DateTime $dateExpedition = null;
} 