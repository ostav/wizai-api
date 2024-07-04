<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findPostsFromParams(array $params)
    {
        $queryBuilder =  $this->createQueryBuilder('p')
            ->leftJoin('p.user','u');
        if (array_key_exists('gender', $params)) {
            $queryBuilder
                ->andWhere('u.gender = :gender')
                ->setParameter('gender', $params['gender']);
        }

        if (array_key_exists('status', $params)) {
            $queryBuilder
                ->andWhere('u.status = :status')
                ->setParameter('status', $params['status']);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
