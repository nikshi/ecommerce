<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="Е-мейлът е вече регистриран")
 * @UniqueEntity("phone", message="Номерът вече се използва")
 */
class User implements UserInterface
{

    public function __construct()
    {
//        $this->lastLogin = new \DateTime();
//        $this->createdOn = new \DateTime();

        $this->roles    = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message="Моля въведете емайл адрес!"
     * )
     *
     * @Assert\Email(
     *     message="Въведете валиден емайл адрес!"
     * )
     */
    private $email;

    /**
     * @var string
     *
     *
     *   @Assert\NotBlank(
     *     message="Моля въведете парола!"
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;


    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", nullable=true, length=255)
     *
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="municipality", type="string", nullable=true, length=255)
     *
     */
    private $municipality;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true, length=255)
     *
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string", nullable=true, length=255)
     *
     */
    private $post_code;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", nullable=true, length=255)
     *
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true, length=255)
     *
     */
    private $phone;

    /**
     * @var Role[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="user_roles", joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},  inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id") })")
     *
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetimetz", nullable=true)
     */
    private $createdOn;

    /**
     * @var Product[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="user")
     *
     */

    private $products;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetimetz", nullable=true)
     */
    private $lastLogin;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return User
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array_map(function(Role $r) {
            return $r->getName();
        }, $this->roles->toArray());
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    public function addRole(Role $role){
        $this->roles->add($role);
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return string
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param string $municipality
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->post_code;
    }

    /**
     * @param string $post_code
     */
    public function setPostCode($post_code)
    {
        $this->post_code = $post_code;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[]|ArrayCollection $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

}

