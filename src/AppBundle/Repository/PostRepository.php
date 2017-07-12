<?php

namespace AppBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getDashboardPosts()
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.id', 'p.datetime', 'p.title', 'p.content', "CONCAT(u.firstName, ' ', u.lastName) AS author", 'p.createdAt', 'p.updatedAt')
            ->leftJoin('p.user', 'u')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getBlogPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'u', 'c', 't')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.datetime', 'DESC')
            ->getQuery()
            ->getResult();
    }
}