<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function postAction(Post $post, Request $request)
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('blog.post', [
                'id'    => $post->getId(),
                'slug'  => $post->getSlug()
            ]);
        }

        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->getPostComments($post->getId());

        return $this->render('blog/post.html.twig', [
            'post'      => $post,
            'comments'  => $comments,
            'form'      => $form->createView()
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
