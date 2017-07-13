<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog.index")
     */
    public function indexAction(EntityManager $entityManager)
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $entityManager->getRepository('AppBundle:Post')->getBlogPosts()
        ]);
    }

    /**
     * @Route("/{id},{slug}.html", name="blog.post")
     */
    public function postAction(Post $post)
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        return $this->render('blog/post.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/category/{id}", name="blog.category")
     */
    public function categoryAction($id)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findBy(['category' => $id]);

        if (!$posts) {
            return $this->redirectToRoute('blog.index');
        }

        return $this->render('blog/category.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/tag/{id}", name="blog.tag")
     */
    public function tagAction($id)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithTag($id);

        if (!$posts) {
            return $this->redirectToRoute('blog.index');
        }

        return $this->render('blog/tag.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/archive/{year}/{month}", name="blog.archive")
     */
    public function archiveAction($year, $month)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsByMonth($year, $month);

        if (!$posts) {
            return $this->redirectToRoute('blog.index');
        }

        return $this->render('blog/archive.html.twig', [
            'posts' => $posts
        ]);
    }
}
