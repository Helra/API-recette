<?php

namespace App\Entity;

use App\Repository\RecipeTotalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeTotalRepository::class)
 */
class RecipeTotal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipeTotals")
     */
    private $Ingredient;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, inversedBy="recipeTotals")
     */
    private $Recipe;

    public function __construct()
    {
        $this->Ingredient = new ArrayCollection();
        $this->Recipe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredient(): Collection
    {
        return $this->Ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->Ingredient->contains($ingredient)) {
            $this->Ingredient[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->Ingredient->contains($ingredient)) {
            $this->Ingredient->removeElement($ingredient);
        }

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipe(): Collection
    {
        return $this->Recipe;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->Recipe->contains($recipe)) {
            $this->Recipe[] = $recipe;
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->Recipe->contains($recipe)) {
            $this->Recipe->removeElement($recipe);
        }

        return $this;
    }
}
