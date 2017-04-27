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

        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Моля въведете Вашето име!"
     * )
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
     *   @Assert\NotBlank(
     *     message="Моля въведете парола!"
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;


    /**
     * @var string
     *
     */
    private $oldPassword;

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
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     *
     */
    private $role;

    /**
     * @var float
     *
     * @ORM\Column(name="cash", type="float", nullable=true)
     */
    private $cash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetimetz", nullable=true)
     */
    private $createdOn;

    /**
     * @var Product[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="user", cascade={"remove"})
     *
     */

    private $products;

    /**
     * @var Orders[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Orders", mappedBy="user", cascade={"remove"})
     */
    private $orders;

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

    /**
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return float
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param float $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
        $role[] = $this->role->getName();
        return $role;
    }

    /**
     * @return Orders[]|ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Orders[]|ArrayCollection $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }
}

