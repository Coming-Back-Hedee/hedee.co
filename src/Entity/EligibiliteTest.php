<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @ORM\Entity()
 */
class EligibiliteTest
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
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $enseigne;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $dateAchat;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     * min = 20,
     * minMessage = "Nous ne traitons que les produits dont le prix d'achat est supérieur à {{limit}}€"
     * )
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEnseigne(): ?string
    {
        return $this->enseigne;
    }

    public function setEnseigne(string $enseigne): self
    {
        $this->enseigne = $enseigne;

        return $this;
    }

    public function getDateAchat(): ?string
    {
        return $this->dateAchat;
    }

    public function setDateAchat(string $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /*public function __construct()
    {
        $this->setDateAchat(new \DateTime($post['dateAchat']));
    }*/
    
}
