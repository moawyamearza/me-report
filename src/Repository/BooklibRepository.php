<?php

namespace App\Repository;

use App\Entity\Booklib;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booklib>
 *
 * @method Booklib|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booklib|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booklib[]    findAll()
 * @method Booklib[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooklibRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booklib::class);
    }

//    /**
//     * @return Booklib[] Returns an array of Booklib objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booklib
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
