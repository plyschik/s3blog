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

    public function getPostsWithCategory($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.category', 'c')
            ->where('c.slug = :slug')
            ->orderBy('p.datetime')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPostsWithTag($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 't')
            ->leftJoin('p.tags', 't')
            ->where('t.slug = :slug')
            ->orderBy('p.datetime')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPostsByMonth($year, $month)
    {
        return $this->createQueryBuilder('p')
            ->where('YEAR(p.datetime) = :year')
            ->andWhere('MONTH(p.datetime) = :month')
            ->orderBy('p.datetime')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getArchive()
    {
        return $this->getEntityManager()->createQuery("
            SELECT DISTINCT YEAR(p.datetime) AS year, MONTH(p.datetime) AS month
            FROM AppBundle:Post p
            ORDER BY year DESC, month DESC
        ")->getResult();
    }
}