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
//        foreach ($datas as $key => $data) {
//            $datas[$key] = $this->remove_accents($data);
//        }
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
        $form->submit($datas);


        if ($form->isValid()) {
            var_dump('Valid');
        } else {
            var_dump('Not Valid');
        }

        if ($form->isSubmitted()) {
            foreach ($datas as $typeEntity => $data) {
                if ($typeEntity === 'Ingredient') {
                    $ingredientTabs = $datas[$typeEntity];
                    foreach ($ingredientTabs as $ingredients) {
                        foreach ($ingredients as $keyIngredient => $ingredient) {

                            if ($keyIngredient === 'id') {
                                $ingredientRecipeTotal[] = $ingredientRepository->find($ingredient);
                                var_dump($ingredientRecipeTotal);
                            } elseif ($keyIngredient === 'name') {
                                if ($ingredientRepository->findOneByName($ingredient)) {
                                    $ingredientRecipeTotal[] = $ingredientRepository->findOneByName($ingredient);
                                }
                            }
                        }
                    }
                }
            }
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

//    /**
//     * Add Recipe Total
//     * @Route("/recipetotal/add", name="RecipeTotalAdd", methods={"PUT"})
//     * @param Request $request
//     * @return JsonResponse|RedirectResponse
//     */
//    public function RecipeTotalAdd(Request $request, IngredientRepository $ingredientRepository)
//    {
//        $recipeTotal = new RecipeTotal();
//
//        $datas = json_decode($request->getContent(), true);
//
//        $errors = [];
//        $ingredientRecipeTotal = [];
//
//
//        foreach ($datas as $typeEntity => $data) {
//            if ($typeEntity === 'Ingredient') {
//                $ingredientTabs = $datas[$typeEntity];
//                foreach ($ingredientTabs as $ingredients) {
//                    foreach ($ingredients as $keyIngredient => $ingredient) {
//
//                        if ($keyIngredient === 'id') {
//                            $ingredientRecipeTotal[] = $ingredientRepository->find($ingredient);
//                            var_dump($ingredientRecipeTotal);
//                        } elseif ($keyIngredient === 'name') {
//                            if ($ingredientRepository->findOneByName($ingredient)) {
//                                $ingredientRecipeTotal[] = $ingredientRepository->findOneByName($ingredient);
//                            } else {
//                                $curl = curl_init();
//
//                                curl_setopt_array($curl, array(
//                                    CURLOPT_URL => "http://127.0.0.1:8000/api/ingredients/add",
//                                    CURLOPT_RETURNTRANSFER => true,
//                                    CURLOPT_ENCODING => "",
//                                    CURLOPT_MAXREDIRS => 10,
//                                    CURLOPT_TIMEOUT => 0,
//                                    CURLOPT_FOLLOWLOCATION => true,
//                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                                    CURLOPT_CUSTOMREQUEST => "PUT",
//                                    CURLOPT_POSTFIELDS => json_encode($ingredients[$keyIngredient], true),
//                                    CURLOPT_HTTPHEADER => array(
//                                        "Content-Type: application/json"
//                                    ),
//                                ));
//                                $response = curl_exec($curl);
//                                curl_close($curl);
//                                $ingredientRecipeTotals[] = $response;
//                            }
//                        } else {
//                            $errors[] = "Mauvais format d'ingrédient";
//                        }
//                    }
//                }
//                var_dump($ingredientRecipeTotals);
//
//            } elseif
//            ($typeEntity === 'Recipe') {
//                $recipes = $datas[$typeEntity];
//            }
//        }
//
//
//        $form = $this->createForm(RecipeTotalType::class, $recipeTotal);
//        $form->submit($data);
//
//        if ($form->isSubmitted()) {
//            echo 'Submit';
//        }
//
//        if ($form->isValid()) {
//            echo 'Valid';
//        }
//
//        if ($form->isSubmitted()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($recipeTotal);
//            $entityManager->flush();
//        }
//
//
//        $id = $recipeTotal->getId();
//        $nameIngredients = [];
//        $recipeOnly = $recipeTotalRepository->find($id);
//        $recipes = $recipeOnly->getRecipe();
//        var_dump($recipes);
//        foreach ($recipes as $recipe) {
//            $titleRecipe = $recipe->getTitle();
//            $subTitleRecipe = $recipe->getSubTitle();
//        }
//        $ingredients = $recipeOnly->getIngredient();
//        foreach ($ingredients as $ingredient) {
//            $nameIngredients[] = $ingredient->getName();
//        }
////        return $this->json(["Id" => $recipes, "Title Recipe" => $titleRecipe, "SubTitle Recipe" => $subTitleRecipe, "ingredients" => $nameIngredients]);
//
//    }


    /**
     * Show Recipe Total
     * @Route("/ingredients/{id}", name="IngredientShow", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
//    public function RecipeShow (Ingredient $ingredient, IngredientRepository $ingredientRepository, int $id) {
//        $nameIngredients = [];
//        $recipeOnly = $ingredient->find($id);
//        $recipes = $recipeOnly->getRecipe();
//        foreach ($recipes as $recipe) {
//            $titleRecipe = $recipe->getTitle();
//            $subTitleRecipe = $recipe->getSubTitle();
//        }
//        $ingredients = $recipeOnly->getIngredient();
//        foreach ($ingredients as $ingredient) {
//            $nameIngredients[] = $ingredient->getName();
//        }
//        return $this->json(["Title Recipe" => $titleRecipe, "SubTitle Recipe" => $subTitleRecipe, "ingredients" => $nameIngredients] );
//    }

//    /**
//     * Show Recipe Total
//     * @Route("/recipetotal/{id}", name="RecipeTotalShow", methods={"GET"})
//     * @param RecipeTotal $recipeTotal
//     * @param RecipeTotalRepository $recipeTotalRepository
//     * @param int $id
//     * @return JsonResponse
//     */
//    public function RecipeShow(RecipeTotal $recipeTotal, RecipeTotalRepository $recipeTotalRepository, int $id)
//    {
//        $nameIngredients = [];
//        $recipeOnly = $recipeTotalRepository->find($id);
//        $recipes = $recipeOnly->getRecipe();
//        foreach ($recipes as $recipe) {
//            $titleRecipe = $recipe->getTitle();
//            $subTitleRecipe = $recipe->getSubTitle();
//        }
//        $ingredients = $recipeOnly->getIngredient();
//        foreach ($ingredients as $ingredient) {
//            $nameIngredients[] = $ingredient->getName();
//        }
//        return $this->json(["Title Recipe" => $titleRecipe, "SubTitle Recipe" => $subTitleRecipe, "ingredients" => $nameIngredients]);
//    }

    private function remove_accents($string)
    {

        if ($string) {
            $chars = array(
                // Decompositions for Latin-1 Supplement.
                'à' => 'a',
                'ª' => 'a',
                'º' => 'o',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'AE',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ð' => 'D',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'TH',
                'ß' => 's',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'ae',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'd',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ü' => 'u',
                'ý' => 'y',
                'þ' => 'th',
                'ÿ' => 'y',
                'Ø' => 'O',
                // Decompositions for Latin Extended-A.
                'Ā' => 'A',
                'ā' => 'a',
                'Ă' => 'A',
                'ă' => 'a',
                'Ą' => 'A',
                'ą' => 'a',
                'Ć' => 'C',
                'ć' => 'c',
                'Ĉ' => 'C',
                'ĉ' => 'c',
                'Ċ' => 'C',
                'ċ' => 'c',
                'Č' => 'C',
                'č' => 'c',
                'Ď' => 'D',
                'ď' => 'd',
                'Đ' => 'D',
                'đ' => 'd',
                'Ē' => 'E',
                'ē' => 'e',
                'Ĕ' => 'E',
                'ĕ' => 'e',
                'Ė' => 'E',
                'ė' => 'e',
                'Ę' => 'E',
                'ę' => 'e',
                'Ě' => 'E',
                'ě' => 'e',
                'Ĝ' => 'G',
                'ĝ' => 'g',
                'Ğ' => 'G',
                'ğ' => 'g',
                'Ġ' => 'G',
                'ġ' => 'g',
                'Ģ' => 'G',
                'ģ' => 'g',
                'Ĥ' => 'H',
                'ĥ' => 'h',
                'Ħ' => 'H',
                'ħ' => 'h',
                'Ĩ' => 'I',
                'ĩ' => 'i',
                'Ī' => 'I',
                'ī' => 'i',
                'Ĭ' => 'I',
                'ĭ' => 'i',
                'Į' => 'I',
                'į' => 'i',
                'İ' => 'I',
                'ı' => 'i',
                'Ĳ' => 'IJ',
                'ĳ' => 'ij',
                'Ĵ' => 'J',
                'ĵ' => 'j',
                'Ķ' => 'K',
                'ķ' => 'k',
                'ĸ' => 'k',
                'Ĺ' => 'L',
                'ĺ' => 'l',
                'Ļ' => 'L',
                'ļ' => 'l',
                'Ľ' => 'L',
                'ľ' => 'l',
                'Ŀ' => 'L',
                'ŀ' => 'l',
                'Ł' => 'L',
                'ł' => 'l',
                'Ń' => 'N',
                'ń' => 'n',
                'Ņ' => 'N',
                'ņ' => 'n',
                'Ň' => 'N',
                'ň' => 'n',
                'ŉ' => 'n',
                'Ŋ' => 'N',
                'ŋ' => 'n',
                'Ō' => 'O',
                'ō' => 'o',
                'Ŏ' => 'O',
                'ŏ' => 'o',
                'Ő' => 'O',
                'ő' => 'o',
                'Œ' => 'OE',
                'œ' => 'oe',
                'Ŕ' => 'R',
                'ŕ' => 'r',
                'Ŗ' => 'R',
                'ŗ' => 'r',
                'Ř' => 'R',
                'ř' => 'r',
                'Ś' => 'S',
                'ś' => 's',
                'Ŝ' => 'S',
                'ŝ' => 's',
                'Ş' => 'S',
                'ş' => 's',
                'Š' => 'S',
                'š' => 's',
                'Ţ' => 'T',
                'ţ' => 't',
                'Ť' => 'T',
                'ť' => 't',
                'Ŧ' => 'T',
                'ŧ' => 't',
                'Ũ' => 'U',
                'ũ' => 'u',
                'Ū' => 'U',
                'ū' => 'u',
                'Ŭ' => 'U',
                'ŭ' => 'u',
                'Ů' => 'U',
                'ů' => 'u',
                'Ű' => 'U',
                'ű' => 'u',
                'Ų' => 'U',
                'ų' => 'u',
                'Ŵ' => 'W',
                'ŵ' => 'w',
                'Ŷ' => 'Y',
                'ŷ' => 'y',
                'Ÿ' => 'Y',
                'Ź' => 'Z',
                'ź' => 'z',
                'Ż' => 'Z',
                'ż' => 'z',
                'Ž' => 'Z',
                'ž' => 'z',
                'ſ' => 's',
                // Decompositions for Latin Extended-B.
                'Ș' => 'S',
                'ș' => 's',
                'Ț' => 'T',
                'ț' => 't',
                // Euro sign.
                '€' => 'E',
                // GBP (Pound) sign.
                '£' => '',
                // Vowels with diacritic (Vietnamese).
                // Unmarked.
                'Ơ' => 'O',
                'ơ' => 'o',
                'Ư' => 'U',
                'ư' => 'u',
                // Grave accent.
                'Ầ' => 'A',
                'ầ' => 'a',
                'Ằ' => 'A',
                'ằ' => 'a',
                'Ề' => 'E',
                'ề' => 'e',
                'Ồ' => 'O',
                'ồ' => 'o',
                'Ờ' => 'O',
                'ờ' => 'o',
                'Ừ' => 'U',
                'ừ' => 'u',
                'Ỳ' => 'Y',
                'ỳ' => 'y',
                // Hook.
                'Ả' => 'A',
                'ả' => 'a',
                'Ẩ' => 'A',
                'ẩ' => 'a',
                'Ẳ' => 'A',
                'ẳ' => 'a',
                'Ẻ' => 'E',
                'ẻ' => 'e',
                'Ể' => 'E',
                'ể' => 'e',
                'Ỉ' => 'I',
                'ỉ' => 'i',
                'Ỏ' => 'O',
                'ỏ' => 'o',
                'Ổ' => 'O',
                'ổ' => 'o',
                'Ở' => 'O',
                'ở' => 'o',
                'Ủ' => 'U',
                'ủ' => 'u',
                'Ử' => 'U',
                'ử' => 'u',
                'Ỷ' => 'Y',
                'ỷ' => 'y',
                // Tilde.
                'Ẫ' => 'A',
                'ẫ' => 'a',
                'Ẵ' => 'A',
                'ẵ' => 'a',
                'Ẽ' => 'E',
                'ẽ' => 'e',
                'Ễ' => 'E',
                'ễ' => 'e',
                'Ỗ' => 'O',
                'ỗ' => 'o',
                'Ỡ' => 'O',
                'ỡ' => 'o',
                'Ữ' => 'U',
                'ữ' => 'u',
                'Ỹ' => 'Y',
                'ỹ' => 'y',
                // Acute accent.
                'Ấ' => 'A',
                'ấ' => 'a',
                'Ắ' => 'A',
                'ắ' => 'a',
                'Ế' => 'E',
                'ế' => 'e',
                'Ố' => 'O',
                'ố' => 'o',
                'Ớ' => 'O',
                'ớ' => 'o',
                'Ứ' => 'U',
                'ứ' => 'u',
                // Dot below.
                'Ạ' => 'A',
                'ạ' => 'a',
                'Ậ' => 'A',
                'ậ' => 'a',
                'Ặ' => 'A',
                'ặ' => 'a',
                'Ẹ' => 'E',
                'ẹ' => 'e',
                'Ệ' => 'E',
                'ệ' => 'e',
                'Ị' => 'I',
                'ị' => 'i',
                'Ọ' => 'O',
                'ọ' => 'o',
                'Ộ' => 'O',
                'ộ' => 'o',
                'Ợ' => 'O',
                'ợ' => 'o',
                'Ụ' => 'U',
                'ụ' => 'u',
                'Ự' => 'U',
                'ự' => 'u',
                'Ỵ' => 'Y',
                'ỵ' => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin).
                'ɑ' => 'a',
                // Macron.
                'Ǖ' => 'U',
                'ǖ' => 'u',
                // Acute accent.
                'Ǘ' => 'U',
                'ǘ' => 'u',
                // Caron.
                'Ǎ' => 'A',
                'ǎ' => 'a',
                'Ǐ' => 'I',
                'ǐ' => 'i',
                'Ǒ' => 'O',
                'ǒ' => 'o',
                'Ǔ' => 'U',
                'ǔ' => 'u',
                'Ǚ' => 'U',
                'ǚ' => 'u',
                // Grave accent.
                'Ǜ' => 'U',
                'ǜ' => 'u',
            );


            $string = strtr($string, $chars);
        }

        return $string;
    }


}