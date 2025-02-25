<?php

namespace App\Service;

// Autres use nécessaires...

use App\Entity\Avarie;
use App\Repository\AvarieRepository;
use Knp\Component\Pager\PaginatorInterface;

class AvarieService
{
    public function __construct(
        private AvarieRepository $avarieRepository,
        private PaginatorInterface $paginator
    ) {}

    /**
     * @param array<string, mixed> $filters
     * @return array<int, Avarie>
     */
    public function getPaginatedAvaries(int $page, array $filters = []): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $qb = $this->avarieRepository->createQueryBuilder('a')
            ->leftJoin('a.vehicule', 'v')
            ->orderBy('a.dateCreation', 'DESC');

        // Ajoutez des filtres si nécessaire
        if (!empty($filters)) {
            // Implémentez la logique de filtrage ici
        }

        return $this->paginator->paginate(
            $qb->getQuery(),
            $page,
            10 // nombre d'éléments par page
        );
    }

    /** @return array<string, mixed> */
    public function getAvarieStats(): array
    {
        // Implémentation
        return [
            'total' => 0,
            'resolved' => 0,
            'pending' => 0
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createAvarie(array $data): Avarie
    {
        $avarie = new Avarie();
        // Implémentation
        return $avarie;
    }
} 