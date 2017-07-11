<?php

namespace AppBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
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
            'categories' => $entityManager->getRepository('AppBundle:Category')->getBlogCategories()
        ]);
    }
}
