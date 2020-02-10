<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    /**
     * @return task[]
     */
    public function findAllTasksByUser($user)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
            SELECT t.id, t.name, t.deadline, t.description, t.priority_id, t.status_id
            FROM task as t
            WHERE t.user_id = :user OR :user = (Select user_id
                From task_user
                Where task_id = t.id)
            ORDER BY t.id DESC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('user' => $user));
        return $stmt->fetchAll();
    }
    
    public function findAllTasks()
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.name as task_name, u.name, u.email, t.deadline')
            ->innerJoin("t.user", 'u')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
