<?php

namespace App\Repository;

use App\Entity\Camion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Camion>
 * @method Camion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Camion|null findOneBy(array<string, mixed> $criteria, ?array<string, string> $orderBy = null)
 * @method Camion[]    findAll()
 * @method Camion[]    findBy(array<string, mixed> $criteria, ?array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
class CamionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Camion::class);
    }

    public function countAvailable(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('c.statut = :statut')
            ->setParameter('statut', 'disponible')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return array<string, int> */
    public function countByStatus(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.statut, COUNT(c) as count')
            ->groupBy('c.statut')
            ->getQuery()
            ->getResult();
    }
} 