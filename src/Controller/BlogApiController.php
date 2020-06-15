<?php


namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\IngredientType;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class BlogApiController extends AbstractController
{

    /**
     * Add Ingredient
     * @Route("/ingredients/add", name="IngredientAdd", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function IngredientAdd(Request $request)
    {
        $ingredient = new Ingredient();
        $datas = json_decode($request->getContent(), true);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->submit($datas);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();
        }

        $id = $ingredient->getId();
        $name = $ingredient->getName();

        return $this->json(['id' => $id, 'name' => $name]);
    }

    /**
     * Add Recipe
     * @Route("/recipes/add", name="RecipeAdd", methods={"PUT"})
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @return JsonResponse
     */
    public function RecipeAdd(Request $request, IngredientRepository $ingredientRepository)
    {
        $recipe = new Recipe();
        $datas = json_decode($request->getContent(), true);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        $dataForms = $datas['Ingredient'];

        foreach ($dataForms as $key => $dataForm) {
            if ($ingredientRepository->findOneByName($dataForm['name'])) {
                $dataForms[$key] = $ingredientRepository->findOneByName($dataForm['name']);
                $form->get('Ingredient')->setData($dataForm[$key]);
            } else {
                $dataForms[$key] = $datas['Ingredient'][$key];
            }
        }
        $datas['Ingredient'] = $dataForms;
        $form->submit($datas);


        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();
        }

        $id = $recipe->getId();

        $title = $recipe->getTitle();
        $subTitle = '';
        if ($recipe->getSubTitle()) {
            $subTitle = $recipe->getSubTitle();
        }
        $nameIngredients = [];
        $ingredients = $recipe->getIngredient();
        foreach ($ingredients as $ingredient) {
            $nameIngredients[] = $ingredient->getName();
        }

        return $this->json(['id' => $id, 'Title' => $title, 'SubTitle' => $subTitle, "Ingredients" => $nameIngredients]);
    }


    /**
     * Show Ingredient
     * @Route("/ingredients/{id}", name="IngredientShow", methods={"GET"})
     * @param IngredientRepository $ingredientRepository
     * @param int $id
     * @return JsonResponse
     */
    public function IngredientShow(IngredientRepository $ingredientRepository, int $id)
    {
        $nameRecipes = [];
        $ingredient = $ingredientRepository->find($id);
        $recipes = $ingredient->getRecipes();
        foreach ($recipes as $recipe) {
            $nameRecipes[] = $recipe->getTitle();
        }
        $ingredientName = $ingredient->getName();
        return $this->json(["name" => $ingredientName, "Recipes" => $nameRecipes]);
    }

    /**
     * Show Recipe
     * @Route("/recipes/{id}", name="RecipeShow", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function RecipeShow(Recipe $recipe, RecipeRepository $recipeRepository, int $id)
    {
        $nameIngredients = [];
        $recipe = $recipeRepository->find($id);
        $titleRecipe = $recipe->getTitle();
        $subTitleRecipe = $recipe->getSubTitle();
        $ingredients = $recipe->getIngredient();
        $id = $recipe->getId();
        foreach ($ingredients as $ingredient) {
            $nameIngredients[] = $ingredient->getName();
        }
        return $this->json(["id" => $id, "Title Recipe" => $titleRecipe, "SubTitle Recipe" => $subTitleRecipe, "ingredients" => $nameIngredients]);
    }

}