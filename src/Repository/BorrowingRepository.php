<?php

namespace App\Repository;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Borrowing>
 */
class BorrowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }
    /**
     * Summary of checkAvailability
     * @param int $book_id
     * @return []|null
     */
    public function checkAvailability($book_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.book_id = :book_id')
            ->setParameter('book_id', $book_id)
            ->andWhere('b.checkin_date is NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Get all books borrowed by a given user with out checkin back
     * @param int $user_id
     * @return []|null
     */
    public function getborrowedBooksByUser($user_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('b.checkin_date is NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Get if a given book is borrowed buy the given user and with out checkin back 
     * @param mixed $book_id
     * @param mixed $user_id
     * @return mixed
     */
    public function getBookBorrowedByUser($book_id, $user_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('b.book_id = :book_id')
            ->setParameter('book_id', $book_id)
            ->andWhere('b.checkin_date is NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteAllRecords()
    {
        return $this->createQueryBuilder('b')
            ->delete()
            ->getQuery()
            ->execute();
    }
}
