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
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipes", cascade={"persist", "remove"})
     */
    private $Ingredient;

    public function __construct()
    {
        $this->Ingredient = new ArrayCollection();
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
}
