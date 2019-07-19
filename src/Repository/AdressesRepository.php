<?php

namespace App\Repository;

use App\Entity\Adresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Adresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adresses[]    findAll()
 * @method Adresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdressesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Adresses::class);
    }

}
