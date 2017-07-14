<?php

namespace AppBundle\Controller\Dashboard;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/dashboard/categories", name="dashboard.categories.list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return $this->render('dashboard/categories/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/dashboard/categories/create", name="dashboard.categories.create")
     */
    public function createAction(Request $request, EntityManager $entityManager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($this->getUser());
            $category->setSlug($this->get('slugger')->getSlug($category->getName()));
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.categories.create.success');

            return $this->redirectToRoute('dashboard.categories.list');
        }

        return $this->render('dashboard/categories/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/categories/edit/{id}", name="dashboard.categories.edit")
     */
    public function editAction(Request $request, Category $category, EntityManager $entityManager)
    {
        if (!$category) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->get('slugger')->getSlug($category->getName()));
            $category->setUpdatedAt(new \DateTime());

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'dashboard.flashMessages.categories.edit.success');

            return $this->redirectToRoute('dashboard.categories.list');
        }

        return $this->render('dashboard/categories/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/categories/delete/{id}", name="dashboard.categories.delete")
     */
    public function deleteAction(Category $category, EntityManager $entityManager)
    {
        if (!$category) {
            throw $this->createNotFoundException();
        }

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'dashboard.flashMessages.categories.delete.success');

        return $this->redirectToRoute('dashboard.categories.list');
    }
}
