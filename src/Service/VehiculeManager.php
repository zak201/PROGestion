<?php

namespace App\Service;

use App\Entity\Vehicule;
use App\Entity\Avarie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class VehiculeManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {}

    public function createVehicule(Vehicule $vehicule, bool $flush = true): void
    {
        if (!$vehicule->getCreatedAt()) {
            $vehicule->setCreatedAt(new \DateTime());
        }
        $vehicule->setUpdatedAt(new \DateTime());
        
        $this->entityManager->persist($vehicule);
        
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function updateStatus(Vehicule $vehicule, string $status): void
    {
        if ($status === 'bloque' && $vehicule->getLot()) {
            $lot = $vehicule->getLot();
            $lot->removeVehicule($vehicule);
            $this->entityManager->persist($lot);
        }
        $vehicule->setStatus($status)
            ->setUpdatedAt(new \DateTime());

        if ($status === 'en_maintenance') {
            $this->createAvarieAutomatique($vehicule);
        }

        $this->entityManager->flush();
    }

    private function createAvarieAutomatique(Vehicule $vehicule): void
    {
        $avarie = new Avarie();
        $avarie->setTitre('Maintenance programmée')
            ->setDescription('Maintenance automatique générée par le système')
            ->setVehicule($vehicule)
            ->setDateCreation(new \DateTime());

        $this->entityManager->persist($avarie);
    }
} 