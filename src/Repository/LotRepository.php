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

    /**
     * Méthode personnalisée pour vérifier les doublons en excluant le lot en cours
     * 
     * @param array<string, mixed> $criteria Les critères de recherche
     * @param array<string, mixed>|null $entityData Les données de l'entité en cours
     */
    public function findDuplicateNumeroLot(array $criteria, ?array $entityData = null): ?Lot
    {
        try {
            $qb = $this->createQueryBuilder('l')
                ->select('l')
                ->where('l.numero_lot = :numero_lot')
                ->setParameter('numero_lot', $criteria['numero_lot']);

            // En mode édition, on exclut le lot en cours
            if ($entityData !== null && isset($entityData['id'])) {
                $qb->andWhere('l.id != :currentId')
                   ->setParameter('currentId', $entityData['id']);
            }

            // On limite à 1 résultat pour éviter les problèmes de résultats multiples
            $qb->setMaxResults(1);

            $result = $qb->getQuery()->getOneOrNullResult();

            // Si on trouve un lot différent avec le même numéro, c'est un doublon
            if ($result !== null && 
                ($entityData === null || $result->getId() !== $entityData['id'])) {
                return $result;
            }

            return null;
        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
            // Log l'erreur pour le débogage
            error_log("NonUniqueResultException dans findDuplicateNumeroLot: " . $e->getMessage());
            // En cas d'erreur, on considère qu'il y a un doublon
            return $this->findOneBy(['numero_lot' => $criteria['numero_lot']]);
        }
    }

    public function findWithAssociations(int $id): ?Lot
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.camion', 'c')
            ->addSelect('c')
            ->leftJoin('l.vehicules', 'v')
            ->addSelect('v')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
