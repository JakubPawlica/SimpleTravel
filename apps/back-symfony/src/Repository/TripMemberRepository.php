<?php

namespace App\Repository;

use App\Entity\TripMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TripMember>
 *
 * @method TripMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripMember[]    findAll()
 * @method TripMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TripMember::class);
    }

//    /**
//     * @return TripMember[] Returns an array of TripMember objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TripMember
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
