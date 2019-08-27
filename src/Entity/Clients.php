<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Adresses;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 * @UniqueEntity(fields="email", message="Cet email est déjà enregistré en base.")
 */
class Clients implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(groups={"inscription"})
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $password;
 
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=80)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true, length=10)
     * @Assert\NotBlank()
     */
    private $numeroTelephone;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idParrain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $codeParrainage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbParrainage;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $solde;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
      
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $passwordRequestedAt;

    /**
    * @var string
    *
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Demandes", mappedBy="client", orphanRemoval=true)
     */
    private $demandes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Adresses", cascade={"persist", "remove"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ModeVersement", inversedBy="clients", cascade={"persist", "remove"})
     */
    private $modeVersement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;


    public function __construct()
    {
        $this->isActive = false;
        $this->solde = 0;
        $this->nbParrainage = 0;
        $this->roles = ['ROLE_USER'];
    
        $this->demandes = new ArrayCollection();
        
    }

    public function bis_construct($form)
    {
        $this->prenom = $form['prenom'];
        $this->nom = $form['nom'];
        //$this->email = $form['email'];
        $this->numeroTelephone = $form['numeroTelephone'];

        $this->adresse = new Adresses();
        $this->adresse->bis_construct($form['adresse']);
        
    }
     
    public function getId()
    {
        return $this->id;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
 
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
 
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(int $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function setIdParrain($idParrain)
    {
        $this->idParrain = $idParrain;
        return $this;
    }

    public function getIdParrain() :?string
    {
        return $this->idParrain;
    }

    public function setCodeParrainage() :self
    {
        $prefixe = ['xnlp', 'xhcs', 'xdhm', 'xluj', 'xftd', 'xmia', 'xmtn', 'xmil'];
        $choix = rand(0,count($prefixe)-1);
        
        $codeParrainage = $prefixe[$choix] . ($this->getId()+417);
        $this->codeParrainage = $codeParrainage;
        return $this;
    }

    public function getCodeParrainage(): ?string
    {
        return $this->codeParrainage;
    }

    public function setNbParrainage($nbParrainage)
    {
        $this->nbParrainage = $nbParrainage;
        return $this;
    }

    public function getNbParrainage()
    {
        return $this->nbParrainage;
    }

    public function setSolde($solde)
    {
        $this->solde = $solde;
        return $this;
    }

    public function getSolde()
    {
        return $this->solde;
    }

    /*
     * Get isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
 
    /*
     * Set isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
  
    public function eraseCredentials()
    {
    }
 
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->password,
            $this->isActive,
        ));
    }
 
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->password,
            $this->isActive,
        ) = unserialize($serialized);
    }
    
    public function getRoles()
    {
        return $this->roles; 
    }
 
    public function setRoles(array $roles)
    {
        if (!in_array('ROLE_USER', $roles))
        {
            $roles[] = 'ROLE_USER';
        }
        foreach ($roles as $role)
        {
            if(substr($role, 0, 5) !== 'ROLE_') {
                throw new InvalidArgumentException("Chaque rôle doit commencer par 'ROLE_'");
            }
        }
        $this->roles = $roles;
        return $this;
    }

        /*
     * Get passwordRequestedAt
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /*
     * Set passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    /*
     * Get token
     */
    public function getToken()
    {
        return $this->token;
    }

    /*
     * Set token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getSalt(){}

    public function getUsername(){}

    

    /**
     * @return Collection|Demandes[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demandes $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setClient($this);
        }

        return $this;
    }

    public function removeDemande(Demandes $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            // set the owning side to null (unless already changed)
            if ($demande->getClient() === $this) {
                $demande->setClient(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?Adresses
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresses $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getModeVersement(): ?ModeVersement
    {
        return $this->modeVersement;
    }

    public function setModeVersement(?ModeVersement $modeVersement): self
    {
        $this->modeVersement = $modeVersement;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}


