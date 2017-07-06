<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/dashboard/posts", name="dashboard.post.list")
     */
    public function listAction()
    {
        return $this->render('dashboard/posts/list.html.twig');
    }

    /**
     * @Route("/dashboard/posts/create", name="dashboard.post.create")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setSlug($this->get('slugger')->getSlug($post->getTitle()));
            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.post.create.success');

            return $this->redirectToRoute('dashboard.post.list');
        }

        return $this->render('dashboard/posts/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
