<?php

namespace AppBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagController extends Controller
{
    /**
     * @Route("/dashboard/tags", name="dashboard.tags.list")
     */
    public function listAction(EntityManager $entityManager)
    {
        $tags = $entityManager->getRepository('AppBundle:Tag')->findAll();

        return $this->render('dashboard/tags/list.html.twig', [
            'tags' => $tags
        ]);
    }
}
