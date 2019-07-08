<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandesRepository")
 */
class Demandes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Clients", cascade={"persist"})
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $enseigne;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $magasinAchat;

    /**
     * @ORM\Column(type="float", length=30)
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *     value = 20
     * )
     */
    private $prixAchat;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\LessThanOrEqual("today UTC+1")
     */
    private $dateAchat;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $categorieProduit;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $marqueProduit;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $referenceProduit;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     */
    private $numeroCommande;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaires;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->nomClient;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

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

    public function getMagasinAchat(): ?string
    {
        return $this->magasinAchat;
    }

    public function setMagasinAchat(string $magasinAchat): self
    {
        $this->magasinAchat = $magasinAchat;

        return $this;
    }
    
    public function getPrixAchat(): ?string
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(string $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getCategorieProduit(): ?string
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(string $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;

        return $this;
    }

    public function getMarqueProduit(): ?string
    {
        return $this->marqueProduit;
    }

    public function setMarqueProduit(string $marqueProduit): self
    {
        $this->marqueProduit = $marqueProduit;

        return $this;
    }

    public function getReferenceProduit(): ?string
    {
        return $this->referenceProduit;
    }

    public function setReferenceProduit(string $referenceProduit): self
    {
        $this->referenceProduit = $referenceProduit;

        return $this;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?string $numeroCommande): self
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }
}
