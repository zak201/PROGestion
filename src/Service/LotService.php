<?php

namespace App\Service;

use App\Entity\Lot;
use App\Repository\LotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Service de gestion des lots
 */
class LotService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LotRepository $lotRepository,
        private readonly PaginatorInterface $paginator,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Récupère les lots avec pagination et filtres
     * @param array<string, mixed> $filters
     */
    public function getPaginatedLots(int $page, array $filters = []): object
    {
        try {
            $query = $this->lotRepository->createQueryBuilder('l')
                ->leftJoin('l.vehicules', 'v')
                ->addSelect('v');

            if (isset($filters['status'])) {
                $query->andWhere('l.status = :status')
                      ->setParameter('status', $filters['status']);
            }

            if (isset($filters['search'])) {
                $query->andWhere('l.numero_lot LIKE :search')
                      ->setParameter('search', '%' . $filters['search'] . '%');
            }

            return $this->paginator->paginate(
                $query->getQuery(),
                $page,
                10
            );
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des lots', [
                'error' => $e->getMessage(),
                'filters' => $filters
            ]);
            throw $e;
        }
    }

    /**
     * Récupère les statistiques des lots
     * @return array<string, mixed>
     */
    public function getLotStats(): array
    {
        try {
            return [
                'total' => $this->lotRepository->count([]),
                'by_status' => $this->lotRepository->countByStatus(),
                'recent' => $this->lotRepository->findRecentLots(5)
            ];
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des statistiques', [
                'error' => $e->getMessage()
            ]);
            return [
                'total' => 0,
                'by_status' => [],
                'recent' => []
            ];
        }
    }

    /**
     * Crée un nouveau lot
     * @param array<string, mixed> $data
     * @throws \Exception Si la création échoue
     */
    public function createLot(array $data): Lot
    {
        try {
            $lot = new Lot();
            $lot->setNumeroLot($this->generateLotNumber());
            $lot->setStatus($data['status'] ?? 'en_attente');
            $lot->setDateCreation(new \DateTimeImmutable());

            if (isset($data['vehicules'])) {
                foreach ($data['vehicules'] as $vehicule) {
                    $lot->addVehicule($vehicule);
                    $vehicule->setStatus('en_lot');
                }
            }

            $this->entityManager->persist($lot);
            $this->entityManager->flush();

            $this->logger->info('Nouveau lot créé', [
                'numero' => $lot->getNumeroLot(),
                'vehicules' => count($lot->getVehicules())
            ]);

            return $lot;
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la création du lot', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Met à jour un lot existant
     * @param array<string, mixed> $data
     * @throws NotFoundHttpException Si le lot n'existe pas
     */
    public function updateLot(int $id, array $data): Lot
    {
        try {
            $lot = $this->lotRepository->find($id);
            if (!$lot) {
                throw new NotFoundHttpException('Lot non trouvé');
            }

            if (isset($data['status'])) {
                $lot->setStatus($data['status']);
            }

            if (isset($data['vehicules'])) {
                // Retirer les anciens véhicules
                foreach ($lot->getVehicules() as $vehicule) {
                    $vehicule->setStatus('disponible');
                    $lot->removeVehicule($vehicule);
                }

                // Ajouter les nouveaux
                foreach ($data['vehicules'] as $vehicule) {
                    $lot->addVehicule($vehicule);
                    $vehicule->setStatus('en_lot');
                }
            }

            $this->entityManager->flush();

            $this->logger->info('Lot mis à jour', [
                'id' => $id,
                'numero' => $lot->getNumeroLot()
            ]);

            return $lot;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la mise à jour du lot', [
                'error' => $e->getMessage(),
                'id' => $id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Génère un numéro de lot unique
     */
    private function generateLotNumber(): string
    {
        try {
            $prefix = date('Ymd');
            $lastLot = $this->lotRepository->findOneBy([], ['id' => 'DESC']);
            $sequence = $lastLot ? (intval(substr($lastLot->getNumeroLot(), -4)) + 1) : 1;
            
            return sprintf('%s%04d', $prefix, $sequence);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la génération du numéro de lot', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 