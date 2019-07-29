<?php

namespace App\Repository;

use App\Entity\AlertePrix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AlertePrix|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlertePrix|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlertePrix[]    findAll()
 * @method AlertePrix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertePrixRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlertePrix::class);
    }

    // /**
    //  * @return AlertePrix[] Returns an array of AlertePrix objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlertePrix
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
