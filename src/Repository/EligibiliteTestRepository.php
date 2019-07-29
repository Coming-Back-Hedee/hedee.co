<?php

namespace App\Repository;

use App\Entity\EligibiliteTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EligibiliteTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method EligibiliteTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method EligibiliteTest[]    findAll()
 * @method EligibiliteTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EligibiliteTestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EligibiliteTest::class);
    }

    // /**
    //  * @return EligibiliteTest[] Returns an array of EligibiliteTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EligibiliteTest
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
