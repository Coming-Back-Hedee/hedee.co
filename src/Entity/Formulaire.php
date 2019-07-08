<?php

namespace App\Entity;

class Formulaire
{
    //informations de commande
    protected $magasin;
    protected $marque;
    protected $reference;
    protected $numero_commande;
    protected $commentaires;
    //informations client   
    protected $nom;
    protected $prenom;
    protected $mail;
    protected $telephone;
    protected $adresse;

    

    /* -------------------------------- METHODES POUR INFORMATIONS DE COMMANDE -------------------------------- */
    public function getMagasin()
    {
        return $this->magasin;
    }

    public function setMagasin($magasin)
    {
        $this->magasin = $magasin;
    }

    public function getMarque()
    {
        return $this->marque;
    }

    public function setMarque($marque)
    {
        $this->marque = $marque;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getNumeroCommande()
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande($numero_commande)
    {
        $this->numero_commande = $numero_commande;
    }

    public function getCommentaires()
    {
        return $this->commentaires;
    }

    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }
    /* -------------------------------- METHODES POUR INFORMATIONS CLIENT -------------------------------- */
    
    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->Telephone = $telephone;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

}
