<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findUpcomingReservations(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.reservationDate >= :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('r.reservationDate', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getTotalGuestsForDate(\DateTime $date): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('SUM(r.numberOfGuests)')
            ->where('r.reservationDate = :date')
            ->setParameter('date', $date);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
