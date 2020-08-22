<?php

namespace App\Repository;

use App\Entity\Apoderado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Apoderado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apoderado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apoderado[]    findAll()
 * @method Apoderado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApoderadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apoderado::class);
    }

    // /**
    //  * @return Apoderado[] Returns an array of Apoderado objects
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
    public function findOneBySomeField($value): ?Apoderado
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
