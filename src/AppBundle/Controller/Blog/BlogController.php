<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Driver\Connection;
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

    public function categoriesPanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/categories.html.twig', [
            'categories' => $entityManager->getRepository('AppBundle:Category')->getBlogCategories()
        ]);
    }

    public function tagsPanelAction(EntityManager $entityManager)
    {
        return $this->render('blog/panels/tags.html.twig', [
            'tags' => $entityManager->getRepository('AppBundle:Tag')->findAll()
        ]);
    }

    public function archivePanelAction(Connection $connection)
    {
        return $this->render('blog/panels/archive.html.twig', [
            'months' => $connection->fetchAll("SELECT DISTINCT YEAR(p.datetime) AS year, MONTHNAME(p.datetime) AS month FROM posts p ORDER BY year DESC, month DESC")
        ]);
    }
}
