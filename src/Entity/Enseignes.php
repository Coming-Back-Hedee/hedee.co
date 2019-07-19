<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Enseignes
 *
 * @ORM\Table(name="enseignes")
 * @ORM\Entity(repositoryClass="App\Repository\EnseignesRepository")
 */
class Enseignes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_enseigne", type="string", length=100, nullable=false)
     */
    private $nomEnseigne;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_enseigne", type="string", length=100, nullable=true)
     */
    private $logoEnseigne;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categories")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEnseigne(): ?string
    {
        return $this->nomEnseigne;
    }

    public function setNomEnseigne(string $nomEnseigne): self
    {
        $this->nomEnseigne = $nomEnseigne;

        return $this;
    }

    public function getLogoEnseigne(): ?string
    {
        return $this->logoEnseigne;
    }

    public function setLogoEnseigne(string $logoEnseigne): self
    {
        $this->logoEnseigne = $logoEnseigne;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }


}
