<?php

namespace App\Event;

use App\Entity\Vehicule;
use Symfony\Contracts\EventDispatcher\Event;

class VehiculeEvent extends Event
{
    public const VEHICULE_CREATED = 'vehicule.created';
    public const VEHICULE_UPDATED = 'vehicule.updated';
    public const VEHICULE_DELETED = 'vehicule.deleted';

    public function __construct(
        private Vehicule $vehicule,
        private string $action
    ) {}

    public function getVehicule(): Vehicule
    {
        return $this->vehicule;
    }

    public function getAction(): string
    {
        return $this->action;
    }
} 