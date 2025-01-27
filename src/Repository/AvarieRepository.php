<?php

namespace App\Repository;

use App\Entity\Avarie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avarie>
 */
class AvarieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avarie::class);
    }

    //    /**
    //     * @return Avarie[] Returns an array of Avarie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Avarie
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countNonResolved(): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.dossier_cloture = :cloture')
            ->setParameter('cloture', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
