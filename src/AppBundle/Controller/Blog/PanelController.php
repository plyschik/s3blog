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
            'tags' => $entityManager->getRepository('AppBundle:Tag')->getBlogTags()
        ]);
    }

    public function archivePanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/archive.html.twig', [
            'months' => $entityManager->getRepository('AppBundle:Post')->getArchive()
        ]);
    }
}
