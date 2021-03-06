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
        $id = $ingredient->getId();
        $ingredientName = $ingredient->getName();
        return $this->json(["id" => $id, "name" => $ingredientName, "Recipes" => $nameRecipes]);
    }

    /**
     * Show Recipe
     * @Route("/recipes/{id}", name="RecipeShow", methods={"GET"})
     * @param RecipeRepository $recipeRepository
     * @param int $id
     * @return JsonResponse
     */
    public function RecipeShow( RecipeRepository $recipeRepository, int $id)
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

    /**
     * Edit Ingredient
     * @Route("/ingredients/{id}", name="IngredientEdit", methods={"POST"})
     * @param int $id
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @return JsonResponse
     */
    public function IngredientEdit( int $id, Request $request, IngredientRepository $ingredientRepository)
    {
        $ingredient = $ingredientRepository->find($id);

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
        $titles = $ingredient->getRecipes();
        $recipes =[];
        foreach ($titles as $title) {
            $recipes =$title->getTitle();
        }

        return $this->json(['id' => $id, 'name' => $name, 'Recipe' => $recipes]);
    }

    //Voir quoi faire des ingrédients

    /**
     * Edit Recipe
     * @Route("/recipes/{id}", name="RecipeEdit", methods={"POST"})
     * @param RecipeRepository $recipeRepository
     * @param int $id
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @return JsonResponse
     */
    public function RecipeEdit(RecipeRepository $recipeRepository, int $id, Request $request, IngredientRepository $ingredientRepository)
    {
        $recipe = $recipeRepository->find($id);

        $datas = json_decode($request->getContent(), true);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if (isset($datas['Ingredient'])) {
            $dataForms = $datas['Ingredient'];

            foreach ($dataForms as $key => $dataForm) {
                if ($ingredientRepository->findOneByName($dataForm['name'])) {
                    $dataForms[$key] = $ingredientRepository->findOneByName($dataForm['name']);
                    $form->get('Ingredient')->setData($dataForm[$key]);
                } else {
                    $dataForms[$key] = $datas['Ingredient'][$key];
                }
            }
        } else {
            $dataForms = $recipe->getIngredient();
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
     * Delete Recipe
     * @Route("/recipes/{id}", name="RecipeDelete", methods={"DELETE"})
     * @param RecipeRepository $recipeRepository
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function RecipeDelete(RecipeRepository $recipeRepository, int $id, Request $request)
    {
        $recipe = $recipeRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
            return $this->json(['Recette effacée']);
        }

    }


}