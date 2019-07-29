<?php

namespace App\Repository;

use App\Entity\Enseignes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Enseignes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignes[]    findAll()
 * @method Enseignes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Enseignes::class);
    }

    public function findAllMatching()
    {
        return $this->createQueryBuilder('u')
            ->select('u.nomEnseigne')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Enseignes[] Returns an array of Enseignes objects
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
    public function findOneBySomeField($value): ?Enseignes
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
