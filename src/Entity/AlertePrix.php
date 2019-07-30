<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlertePrixRepository")
 */
class AlertePrix
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enseigne;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $differencePrix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Demandes", inversedBy="alertesPrix")
     */
    private $dossier;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clotureR;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clotureNR;
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDifferencePrix(): ?float
    {
        return $this->differencePrix;
    }

    public function setDifferencePrix(float $differencePrix): self
    {
        $this->differencePrix = $differencePrix;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDossier(): ?Demandes
    {
        return $this->dossier;
    }

    public function setDossier(?Demandes $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getClotureR(): ?bool
    {
        return $this->clotureR;
    }

    public function setClotureR(?bool $clotureR): self
    {
        $this->clotureR = $clotureR;

        return $this;
    }

    public function getClotureNR(): ?bool
    {
        return $this->clotureNR;
    }

    public function setClotureNR(?bool $clotureNR): self
    {
        $this->clotureNR = $clotureNR;

        return $this;
    }
}
