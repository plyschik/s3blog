<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * @Route("/dashboard/posts", name="dashboard.posts.list")
     */
    public function listAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPosts();

        return $this->render('dashboard/posts/list.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/dashboard/posts/create", name="dashboard.posts.create")
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

            return $this->redirectToRoute('dashboard.posts.list');
        }

        return $this->render('dashboard/posts/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/posts/edit/{id}", name="dashboard.posts.edit")
     */
    public function editAction(Request $request, Post $post)
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->get('slugger')->getSlug($post->getTitle()));
            $post->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.post.edit.success');

            return $this->redirectToRoute('dashboard.posts.list');
        }

        return $this->render('dashboard/posts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/posts/delete/{id}", name="dashboard.posts.delete")
     */
    public function deleteAction(Post $post, EntityManager $entityManager)
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'dashboard.flashMessages.post.delete.success');

        return $this->redirectToRoute('dashboard.posts.list');
    }
}
