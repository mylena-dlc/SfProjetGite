<?php

namespace App\Repository;

use App\Entity\Period;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Period>
 *
 * @method Period|null find($id, $lockMode = null, $lockVersion = null)
 * @method Period|null findOneBy(array $criteria, array $orderBy = null)
 * @method Period[]    findAll()
 * @method Period[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Period::class);
    }

    // Fonction pour vérifier si les dates chevauchent les périodes existantes en BDD
    public function findOverlappingPerriods(\DateTimeInterface $startDate, \DateTimeInterface $endDate, int $excludePeriodId = null) 
    {
        $qb = $this->createQueryBuilder('p')
        ->where('p.startDate <= :endDate')
        ->andWhere('p.endDate >= :startDate')
        ->setParameters([
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

    if ($excludePeriodId) {
        $qb->andWhere('p.id != :excludePeriodId')
            ->setParameter('excludePeriodId', $excludePeriodId);
    }

    return $qb->getQuery()->getResult();
}

    }

//    /**
//     * @return Period[] Returns an array of Period objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Period
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

