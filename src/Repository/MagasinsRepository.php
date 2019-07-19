<?php

namespace App\Repository;

use App\Entity\Magasins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Magasins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasins[]    findAll()
 * @method Magasins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Magasins::class);
    }

    // /**
    //  * @return Magasins[] Returns an array of Magasins objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByJustNom($value)
    {
        return $this->createQueryBuilder('m')
            ->select('m.nomMagasin')
            ->andWhere('m.enseigne = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /*
    public function findOneBySomeField($value): ?Magasins
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
