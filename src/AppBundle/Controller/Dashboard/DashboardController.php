<?php

namespace AppBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/dashboard", name="dashboard.index")
     */
    public function indexAction()
    {
        return $this->render('dashboard/index.html.twig');
    }

    public function categoriesPanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/categories.html.twig', [
            'categories' => $this->entityManager->getRepository('AppBundle:Category')->getBlogCategories()
        ]);
    }

    public function tagsPanelAction()
    {
        return $this->render('blog/panels/tags.html.twig', [
            'tags' => $this->entityManager->getRepository('AppBundle:Tag')->findAll()
        ]);
    }
}
