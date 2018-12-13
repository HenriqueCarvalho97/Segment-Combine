<?php

namespace App\Repository;

use App\Entity\HouseImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HouseImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HouseImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HouseImage[]    findAll()
 * @method HouseImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HouseImage::class);
    }

    // /**
    //  * @return HouseImage[] Returns an array of HouseImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HouseImage
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
