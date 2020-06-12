<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=RecipeTotal::class, mappedBy="Ingredient")
     */
    private $recipeTotals;

    public function __construct()
    {
        $this->recipeTotals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|RecipeTotal[]
     */
    public function getRecipeTotals(): Collection
    {
        return $this->recipeTotals;
    }

    public function addRecipeTotal(RecipeTotal $recipeTotal): self
    {
        if (!$this->recipeTotals->contains($recipeTotal)) {
            $this->recipeTotals[] = $recipeTotal;
            $recipeTotal->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipeTotal(RecipeTotal $recipeTotal): self
    {
        if ($this->recipeTotals->contains($recipeTotal)) {
            $this->recipeTotals->removeElement($recipeTotal);
            $recipeTotal->removeIngredient($this);
        }

        return $this;
    }
}
