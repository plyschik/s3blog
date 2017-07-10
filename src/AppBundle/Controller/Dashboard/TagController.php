<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    /**
     * @Route("/dashboard/tags", name="dashboard.tags.list")
     */
    public function listAction(EntityManager $entityManager)
    {
        $tags = $entityManager->getRepository('AppBundle:Tag')->findAll();

        return $this->render('dashboard/tags/list.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/dashboard/tags/create", name="dashboard.tags.create")
     */
    public function createAction(Request $request, EntityManager $entityManager)
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setUser($this->getUser());
            $tag->setCreatedAt(new \DateTime());
            $tag->setUpdatedAt(new \DateTime());

            $entityManager->persist($tag);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.tags.create.success');

            return $this->redirectToRoute('dashboard.tags.list');
        }

        return $this->render('dashboard/tags/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
