<?php


namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeTotal;
use App\Repository\RecipeTotalRepository;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class BlogApiController
{
    /**
     * Edit Recipe
     * @Route("/recipes/{id}", name="RecipeEdit")
     * @param int $id
     * @param RecipeTotalRepository $recipeTotalRepository
     */
    public function RecipeEdit (int $id, RecipeTotalRepository $recipeTotalRepository, Ingredient $ingredient, Recipe $recipe) {
        $recipeOnly = $recipeTotalRepository->find($id);
        $responseIngredient = $recipeOnly->addIngredient($ingredient);
    }




}