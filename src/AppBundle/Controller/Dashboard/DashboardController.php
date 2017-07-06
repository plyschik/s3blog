<?php

namespace AppBundle\Controller\Dashboard;

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
}
