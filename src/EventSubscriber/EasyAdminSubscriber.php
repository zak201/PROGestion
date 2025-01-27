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
    private $entityManager;

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

    public function beforePersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, 'setDateCreation')) {
            $entity->setDateCreation(new \DateTime());
        }

        if ($entity instanceof Lot) {
            $this->generateLotNumber($entity);
        }
    }

    public function beforeUpdate(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, 'setDateModification')) {
            $entity->setDateModification(new \DateTime());
        }
    }

    private function generateLotNumber(Lot $lot)
    {
        if (!$lot->getNumero()) {
            $prefix = date('Ymd');
            $lastLot = $this->entityManager->getRepository(Lot::class)
                ->findOneBy([], ['id' => 'DESC']);
            
            $sequence = $lastLot ? (intval(substr($lastLot->getNumero(), -4)) + 1) : 1;
            $lot->setNumero(sprintf('%s%04d', $prefix, $sequence));
        }
    }
} 