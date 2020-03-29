<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Klasa koja predstavlja registriranog korisnika
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
     * @ORM\Column(type="string")
     */
    private $name;
	
	/**
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;
	
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    private $plainPassword;
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
	
	public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

      public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

	public function getSurname()
    {
        return $this->surname;
    }
	
	public function getEmail()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
		if ($this->username === 'Admin') {
			return ['ROLE_ADMIN'];
        }
        return ['ROLE_USER'];
    }
	
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
		
	public function setUsername($username)
    {
        $this->username = $username;
    }
	
	public function setName($name)
    {
        $this->name = $name;
    }
	
	public function setSurname($surname)
    {
        $this->surname = $surname;
    }
	
	public function setEmail($email)
    {
        $this->email = $email;
    }
	
	public function setPassword($password)
    {
        $this->password = $password;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    // * @see \Serializable::unserialize() 
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }
}
