<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MagasinsRepository")
 */
class Magasins
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nomMagasin;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $enseigne;

     /**
     * @ORM\OneToOne(targetEntity="App\Entity\Adresses", cascade={"persist"})
     */
    private $adresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMagasin(): ?string
    {
        return $this->nomMagasin;
    }

    public function setNomMagasin(string $nomMagasin): self
    {
        $this->nomMagasin = $nomMagasin;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(Adresses $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
