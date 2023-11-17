<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // Fonction pour vérifier les chevauchements de dates de réservation en base de données
    public function findOverlappingReservations(\DateTimeInterface $arrivalDate, \DateTimeInterface $departureDate, int $excludeReservationId = null) 
    {
    // Initialise un objet QueryBuilder lié à l'entité des réservations
    $qb = $this->createQueryBuilder('r')
        // Vérifie si la date d'arrivée de la réservation en base est inférieure ou égale à la date de départ spécifiée
        ->where('r.departureDate >= :arrivalDate')
        // Vérifie si la date de départ de la réservation en base est supérieure ou égale à la date d'arrivée spécifiée
        ->andWhere('r.arrivalDate <= :departureDate')
        // Définit les valeurs des paramètres
        ->setParameters([
            'arrivalDate' => $arrivalDate,
            'departureDate' => $departureDate,
        ]);

    // Si un ID de réservation est fourni, exclut cette réservation de la recherche
    if ($excludeReservationId) {
        $qb->andWhere('r.id != :excludeReservationId')
            ->setParameter('excludeReservationId', $excludeReservationId);
    }

    // Exécute la requête et renvoie les résultats
    return $qb->getQuery()->getResult();
}

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
