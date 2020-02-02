<?php

namespace App\Repository;

use App\Entity\UsersTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UsersTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersTask[]    findAll()
 * @method UsersTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersTask::class);
    }

    // /**
    //  * @return UsersTask[] Returns an array of UsersTask objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersTask
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
