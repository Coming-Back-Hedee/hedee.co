<?php

namespace App\Entity;

	
use Doctrine\ORM\EntityManager;
use App\Repository\DemandesRepository;
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
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $enseigne;

    /* /**
     * @ORM\Column(type="string", length=100, nullable=true)
    
    private $magasinAchat; */

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
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $urlProduit;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Url
     */
    private $marqueProduit;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $referenceProduit;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     */
    private $numeroCommande;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     */
    private $numeroDossier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facture;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clients", inversedBy="demandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    public function __construct()
    {
        $this->statut =  "En cours";
        //$this->setNumeroDossier();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setNumeroDossier($count)
    {
        $this->numeroDossier = 1417 + $count;
        return $this;
    }

    public function getNumeroDossier()
    {
        return $this->numeroDossier;
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

    /*public function getMagasinAchat(): ?string
    {
        return $this->magasinAchat;
    }

    public function setMagasinAchat(string $magasinAchat): self
    {
        $this->magasinAchat = $magasinAchat;

        return $this;
    }*/
    
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


    public function getUrlProduit(): ?string
    {
        return $this->urlProduit;
    }

    public function setUrlProduit(string $urlProduit): self
    {
        $this->urlProduit = $urlProduit;

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

    public function getCommentaires()
    {
        return $this->commentaires;
    }

    public function setCommentaires(?text $commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }
    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(?string $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?text $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
