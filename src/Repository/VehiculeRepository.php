<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return array<string, int>
     */
    public function countByStatus(): array
    {
        return $this->createQueryBuilder('v')
            ->select('v.statut, COUNT(v) as count')
            ->groupBy('v.statut')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<int, Vehicule>
     */
    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.lot', 'l')
            ->leftJoin('v.navire', 'n');

        if (isset($filters['statut'])) {
            $qb->andWhere('v.statut = :statut')
               ->setParameter('statut', $filters['statut']);
        }

        if (isset($filters['marque'])) {
            $qb->andWhere('v.marque LIKE :marque')
               ->setParameter('marque', '%'.$filters['marque'].'%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Vehicule[]
     */
    public function findRecentVehicules(int $limit): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
