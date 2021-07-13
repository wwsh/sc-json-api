<?php

namespace App\Repository;

use App\Entity\ShoutData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoutData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoutData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoutData[]    findAll()
 * @method ShoutData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoutDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoutData::class);
    }

    // /**
    //  * @return ShoutData[] Returns an array of ShoutData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShoutData
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function deleteOlder(\DateTime $moment): void
    {
        $this->createQueryBuilder('s')
            ->delete('App:ShoutData', 's')
            ->andWhere('s.created < :moment')
            ->setParameter('moment', $moment)
            ->getQuery()
            ->execute();
    }
}
