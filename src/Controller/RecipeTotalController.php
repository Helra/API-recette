<?php

namespace App\Controller;

use App\Entity\RecipeTotal;
use App\Form\RecipeTotalType;
use App\Repository\RecipeTotalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe/total")
 */
class RecipeTotalController extends AbstractController
{
    /**
     * @Route("/", name="recipe_total_index", methods={"GET"})
     */
    public function index(RecipeTotalRepository $recipeTotalRepository): Response
    {
        return $this->render('recipe_total/index.html.twig', [
            'recipe_totals' => $recipeTotalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="recipe_total_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipeTotal = new RecipeTotal();
        $form = $this->createForm(RecipeTotalType::class, $recipeTotal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipeTotal);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_total_index');
        }

        return $this->render('recipe_total/new.html.twig', [
            'recipe_total' => $recipeTotal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_total_show", methods={"GET"})
     */
    public function show(RecipeTotal $recipeTotal): Response
    {
        return $this->render('recipe_total/show.html.twig', [
            'recipe_total' => $recipeTotal,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_total_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RecipeTotal $recipeTotal): Response
    {
        $form = $this->createForm(RecipeTotalType::class, $recipeTotal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_total_index');
        }

        return $this->render('recipe_total/edit.html.twig', [
            'recipe_total' => $recipeTotal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_total_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RecipeTotal $recipeTotal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeTotal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipeTotal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_total_index');
    }
}
