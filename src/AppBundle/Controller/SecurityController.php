<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/signin", name="blog.signin")
     */
    public function signInAction()
    {

    }

    /**
     * @Route("/signup", name="blog.signup")
     */
    public function signUpAction()
    {

    }
}
