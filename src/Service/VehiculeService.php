<?php

namespace App\Service;

use App\Entity\Vehicule;
use App\Event\VehiculeEvent;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Component\Pager\PaginatorInterface;

class VehiculeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehiculeRepository $vehiculeRepository,
        private EventDispatcherInterface $dispatcher,
        private PaginatorInterface $paginator
    ) {}

    /**
     * @param array<string, mixed> $filters
     */
    public function getPaginatedVehicules(int $page, array $filters = []): object
    {
        $query = $this->vehiculeRepository->createQueryBuilder('v')
            ->leftJoin('v.lot', 'l')
            ->addSelect('l')
            ->andWhere('v.status = :status')
            ->setParameter('status', $filters['status']);

        if (isset($filters['marque'])) {
            $query->andWhere('v.marque LIKE :marque')
                  ->setParameter('marque', '%' . $filters['marque'] . '%');
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $page,
            10
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Vehicule
     */
    public function createVehicule(array $data): Vehicule
    {
        $vehicule = new Vehicule();
        $vehicule->setNumeroChassis($data['numeroChassis']);
        $vehicule->setMarque($data['marque']);
        $vehicule->setStatus($data['status'] ?? 'disponible');

        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(
            new VehiculeEvent($vehicule, VehiculeEvent::VEHICULE_CREATED),
            VehiculeEvent::VEHICULE_CREATED
        );

        return $vehicule;
    }

    public function getVehiculeById(int $id): Vehicule
    {
        $vehicule = $this->vehiculeRepository->find($id);
        if (!$vehicule) {
            throw new NotFoundHttpException('Véhicule non trouvé');
        }
        return $vehicule;
    }

    /** @return array<string, mixed> */
    public function getVehiculeStats(): array
    {
        return [
            'total' => $this->vehiculeRepository->count([]),
            'by_status' => $this->vehiculeRepository->countByStatus()
        ];
    }
} 