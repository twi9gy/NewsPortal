<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */

    public function findByClass($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.class = :val')
            ->setParameter('val', $value)
            ->orderBy('n.date_added', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByClassForSlider($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.class = :val')
            ->setParameter('val', $value)
            ->orderBy('n.date_added', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByClassForHome($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.class = :val')
            ->setParameter('val', $value)
            ->orderBy('n.date_added', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findForMain()
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.date_added', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
