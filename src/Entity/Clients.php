<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @ORM\Column(type="string", length=128)
     */
    private $password;
 
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\NotBlank()
     */
    private $numero_telephone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Adresses", cascade={"persist"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_parrain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $code_parrainage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points_fidelite;

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

    public function __construct()
    {
        $this->isActive = true;
        $this->roles = ['ROLE_USER'];
    }
     
    /*
     * Get id
     */
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
 
    /*
     * Get email
     */
    public function getEmail()
    {
        return $this->email;
    }
 
    /*
     * Set email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->numero_telephone;
    }

    public function setNumeroTelephone(int $numero_telephone): self
    {
        $this->numero_telephone = $numero_telephone;

        return $this;
    }

    public function getAdresse(): ?Adresses
    {
        return $this->adresse;
    }

    public function setAdresse(Adresses $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function setIdParrain($id_parrain)
    {
        $this->id_parrain = $id_parrain;
        return $this;
    }

    public function getIdParrain()
    {
        return $this->id_parrain;
    }

    /*public function setCodeParrainage()
    {
        $code = substr($user->getNom(), 0, 2) . $user->getId() . $user->getPrenom()[0] . substr($user->getCodePostal(), 2, 3);
        $this->code_parrainage = $code;
        return $this;
    }*/

    public function getCodeParrainage()
    {
        return $this->code_parrainage;
    }

    public function setPointFidelite($points_fidelite)
    {
        $this->points_fidelite = $points_fidelite;
        return $this;
    }

    public function getPointsFidelite()
    {
        return $this->points_fidelite;
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
}


