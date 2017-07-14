<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/{id},{slug}.html", name="blog.post", requirements={"id": "\d+", "slug": "^[a-z0-9-]+$"})
     * @Method({"GET", "POST"})
     * @ParamConverter("post", class="AppBundle:Post", options={"mapping": {"id": "id", "slug": "slug"}})
     *
     * @param Request $request
     * @param Post $post
     * @param EntityManager $em
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, Post $post, EntityManager $em)
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $parameters['post'] = $post;

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form = $this->createForm(CommentType::class, $comment = new Comment());
            $form->handleRequest($request);

            $parameters['form'] = $form->createView();

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setUser($this->getUser());
                $comment->setPost($post);
                $comment->setCreatedAt(new \DateTime('now'));
                $comment->setUpdatedAt(new \DateTime('now'));

                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('blog.post', [
                    'id'    => $post->getId(),
                    'slug'  => $post->getSlug()
                ]);
            }
        }

        $parameters['comments'] = $em->getRepository('AppBundle:Comment')->getPostComments($post->getId());

        return $this->render('blog/post.html.twig', $parameters);
    }

    /**
     * @Route("/category/{slug}", name="blog.category")
     */
    public function categoryAction($slug)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithCategory($slug);

        if (!$posts) {
            return $this->redirectToRoute('blog.index');
        }
        
        return $this->render('blog/category.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/tag/{slug}", name="blog.tag")
     */
    public function tagAction($slug)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithTag($slug);

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
