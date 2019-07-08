<?php

namespace App\Entity;

class TestEligibilite
{
    protected $categorie;
    protected $enseigne;
    protected $dateAchat;
    protected $prix;
    protected $remise;
     

    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }
    
    public function getEnseigne()
    {
        return $this->enseigne;
    }

    public function setEnseigne($enseigne)
    {
        $this->enseigne = $enseigne;
    }

    public function getDateAchat()
    {
        return $this->dateAchat;
    }

    public function setDate($dateAchat)
    {
        $this->dateAchat = $dateAchat;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getRemise()
    {
        return $this->remise;
    }

    public function setRemise($remise)
    {
        $this->remise = $remise;
    }

}
