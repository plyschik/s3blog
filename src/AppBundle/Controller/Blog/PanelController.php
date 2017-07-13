<?php

namespace AppBundle\Controller\Blog;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PanelController extends Controller
{
    public function categoriesPanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/categories.html.twig', [
            'categories' => $entityManager->getRepository('AppBundle:Category')->getBlogCategories()
        ]);
    }

    public function tagsPanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/tags.html.twig', [
            'tags' => $entityManager->getRepository('AppBundle:Tag')->findAll()
        ]);
    }

    public function archivePanelAction(Connection $connection)
    {
        return $this->render('blog/panels/archive.html.twig', [
            'months' => $connection->fetchAll("SELECT DISTINCT YEAR(p.datetime) AS year, MONTH(p.datetime) AS month, CONCAT(MONTHNAME(p.datetime), ' ', YEAR('p.datetime')) AS monthyear FROM posts p ORDER BY year DESC, month DESC")
        ]);
    }
}
