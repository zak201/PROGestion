<?php

namespace App\EventSubscriber;

use App\Event\VehiculeEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VehiculeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            VehiculeEvent::VEHICULE_CREATED => 'onVehiculeCreated',
            VehiculeEvent::VEHICULE_UPDATED => 'onVehiculeUpdated',
            VehiculeEvent::VEHICULE_DELETED => 'onVehiculeDeleted',
        ];
    }

    public function onVehiculeCreated(VehiculeEvent $event): void
    {
        $vehicule = $event->getVehicule();
        $this->logger->info('Nouveau véhicule créé', [
            'chassis' => $vehicule->getNumeroChassis(),
            'marque' => $vehicule->getMarque()
        ]);
    }

    public function onVehiculeUpdated(VehiculeEvent $event): void
    {
        $vehicule = $event->getVehicule();
        $this->logger->info('Véhicule mis à jour', [
            'chassis' => $vehicule->getNumeroChassis()
        ]);
    }

    public function onVehiculeDeleted(VehiculeEvent $event): void
    {
        $vehicule = $event->getVehicule();
        $this->logger->info('Véhicule supprimé', [
            'chassis' => $vehicule->getNumeroChassis()
        ]);
    }
} 