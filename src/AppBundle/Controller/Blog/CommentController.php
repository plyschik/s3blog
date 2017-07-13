<?php

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * @Route("/comment/delete/{id}", name="blog.comment.delete", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param Request $request
     * @param Comment $comment
     * @param EntityManager $em
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Comment $comment, EntityManager $em)
    {
        $this->denyAccessUnlessGranted('delete', $comment);

        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
