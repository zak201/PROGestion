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

    public function findRecentLots(int $limit): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getMonthlyStats(): array
    {
        return $this->createQueryBuilder('l')
            ->select('MONTH(l.dateCreation) as month')
            ->addSelect('COUNT(l) as count')
            ->where('l.dateCreation >= :year_start')
            ->setParameter('year_start', new \DateTime('first day of january this year'))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByStatus(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.statut, COUNT(l) as count')
            ->groupBy('l.statut')
            ->getQuery()
            ->getResult();
    }
}
