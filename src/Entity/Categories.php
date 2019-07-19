<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 * 
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_categorie", type="string", length=100, nullable=false)
     */
    private $nomCategorie;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Marques", mappedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marques;

    public function __construct()
    {
        $this->marques = new ArrayCollection();
    }

    public function getIdCategorie(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection|Marques[]
     */
    public function getMarques(): Collection
    {
        return $this->marques;
    }

    public function addMarque(Marques $marque): self
    {
        if (!$this->marques->contains($marque)) {
            $this->marques[] = $marque;
            $marque->addCatgory($this);
        }

        return $this;
    }

    public function removeMarque(Marques $marque): self
    {
        if ($this->marques->contains($marque)) {
            $this->marques->removeElement($marque);
            $marque->removeCatgory($this);
        }

        return $this;
    }


}
