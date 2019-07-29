<?php

namespace App\Repository;

use App\Entity\CorrespCPVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CorrespCPVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorrespCPVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorrespCPVille[]    findAll()
 * @method CorrespCPVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrespCPVilleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CorrespCPVille::class);
    }

    // /**
    //  * @return CorrespCPVille[] Returns an array of CorrespCPVille objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CorrespCPVille
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
