<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enseignes
 *
 * @ORM\Table(name="enseignes")
 * @ORM\Entity
 */
class Enseignes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_enseigne", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEnseigne;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_enseigne", type="string", length=100, nullable=false)
     */
    private $nomEnseigne;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_enseigne", type="string", length=100, nullable=false)
     */
    private $logoEnseigne;

    /**
     * @var string
     *
     * @ORM\Column(name="info_enseigne", type="text", length=65535, nullable=false)
     */
    private $infoEnseigne;

    /**
     * @var int
     *
     * @ORM\Column(name="delai_retour", type="integer", nullable=false)
     */
    private $delaiRetour;

    public function getIdEnseigne(): ?int
    {
        return $this->idEnseigne;
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

    public function getInfoEnseigne(): ?string
    {
        return $this->infoEnseigne;
    }

    public function setInfoEnseigne(string $infoEnseigne): self
    {
        $this->infoEnseigne = $infoEnseigne;

        return $this;
    }

    public function getDelaiRetour(): ?int
    {
        return $this->delaiRetour;
    }

    public function setDelaiRetour(int $delaiRetour): self
    {
        $this->delaiRetour = $delaiRetour;

        return $this;
    }


}
