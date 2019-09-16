<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeVersementRepository")
 */
class ModeVersement
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
    private $swiftBic;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $proprietaire;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Clients", mappedBy="modeVersement", cascade={"persist", "remove"})
     */
    private $clients;
    
    public function bis_construct($form)
    {
        
        $this->proprietaire = $form['proprietaire'];
        $this->iban = ucfirst($form['iban']);
        $this->swiftBic = $form['swiftBic'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSwiftBic(): ?string
    {
        return $this->swiftBic;
    }

    public function setSwiftBic(string $swiftBic): self
    {
        $this->swiftBic = $swiftBic;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->proprietaire;
    }

    public function setProprietaire(string $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): self
    {
        $this->clients = $clients;

        // set (or unset) the owning side of the relation if necessary
        $newModeVersement = $clients === null ? null : $this;
        if ($newModeVersement !== $clients->getModeVersement()) {
            $clients->setModeVersement($newModeVersement);
        }

        return $this;
    }
}
