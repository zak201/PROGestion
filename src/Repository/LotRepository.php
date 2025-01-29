<?php

namespace App\Repository;

use App\Entity\Lot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lot>
 */
class LotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lot::class);
    }

    //    /**
    //     * @return Lot[] Returns an array of Lot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lot
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countActive(): int
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l)')
            ->where('l.statut = :statut')
            ->setParameter('statut', 'en_cours')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return array<int, Lot>
     */
    public function findRecentLots(int $limit): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<string, mixed>
     */
    public function getMonthlyStats(): array
    {
        // Implémentation
        return [];
    }

    /**
     * @return array<string, int>
     */
    public function countByStatus(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.statut, COUNT(l) as count')
            ->groupBy('l.statut')
            ->getQuery()
            ->getResult();
    }
}
