<?php

namespace App\Repository;

use App\Entity\ModeVersement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModeVersement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeVersement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeVersement[]    findAll()
 * @method ModeVersement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeVersementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeVersement::class);
    }

    // /**
    //  * @return ModeVersement[] Returns an array of ModeVersement objects
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

    /*
    public function findOneBySomeField($value): ?ModeVersement
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
