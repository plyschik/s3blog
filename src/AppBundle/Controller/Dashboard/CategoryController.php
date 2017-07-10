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
}
