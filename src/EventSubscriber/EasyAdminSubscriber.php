<?php

namespace App\EventSubscriber;

use App\Entity\Lot;
use App\Entity\Vehicule;
use App\Entity\Avarie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['beforePersist'],
            BeforeEntityUpdatedEvent::class => ['beforeUpdate'],
        ];
    }

    public function beforePersist(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, 'setDateCreation')) {
            $entity->setDateCreation(new \DateTime());
        }

        if ($entity instanceof Lot) {
            $this->generateLotNumber($entity);
        }
    }

    public function beforeUpdate(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, 'setDateModification')) {
            $entity->setDateModification(new \DateTime());
        }

        if ($entity instanceof Lot) {
            // Logique de mise à jour
        }
    }

    private function generateLotNumber(Lot $lot): string
    {
        $prefix = 'LOT';
        $lastLot = $this->entityManager->getRepository(Lot::class)
            ->findOneBy([], ['id' => 'DESC']);
        
        $nextNumber = $lastLot ? (int)substr($lastLot->getNumero(), 3) + 1 : 1;
        $numero = sprintf('%s%06d', $prefix, $nextNumber);
        $lot->setNumero($numero);
        
        return $numero;
    }
} 