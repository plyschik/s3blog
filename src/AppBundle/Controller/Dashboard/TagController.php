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
            $tag->setSlug($this->get('slugger')->getSlug($tag->getName()));
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

    /**
     * @Route("/dashboard/tags/edit/{id}", name="dashboard.tags.edit")
     */
    public function editAction(Request $request, Tag $tag, EntityManager $entityManager)
    {
        if (!$tag) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setSlug($this->get('slugger')->getSlug($tag->getName()));
            $tag->setUpdatedAt(new \DateTime());

            $entityManager->persist($tag);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.tags.edit.success');

            return $this->redirectToRoute('dashboard.tags.list');
        }

        return $this->render('dashboard/tags/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/tags/delete/{id}", name="dashboard.tags.delete")
     */
    public function deleteAction(Tag $tag, EntityManager $entityManager)
    {
        if (!$tag) {
            throw $this->createNotFoundException();
        }

        $entityManager->remove($tag);
        $entityManager->flush();

        $this->addFlash('success', 'dashboard.flashMessages.tags.delete.success');

        return $this->redirectToRoute('dashboard.tags.list');
    }
}
