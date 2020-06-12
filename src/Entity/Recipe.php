<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
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
    private $Title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $SubTitle;

    /**
     * @ORM\ManyToMany(targetEntity=RecipeTotal::class, mappedBy="Recipe")
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

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->SubTitle;
    }

    public function setSubTitle(?string $SubTitle): self
    {
        $this->SubTitle = $SubTitle;

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
            $recipeTotal->addRecipe($this);
        }

        return $this;
    }

    public function removeRecipeTotal(RecipeTotal $recipeTotal): self
    {
        if ($this->recipeTotals->contains($recipeTotal)) {
            $this->recipeTotals->removeElement($recipeTotal);
            $recipeTotal->removeRecipe($this);
        }

        return $this;
    }
}
