<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function signUpAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));

            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'blog.flashMessages.signup.success');

            return $this->redirectToRoute('blog.index');
        }

        return $this->render('blog/security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
