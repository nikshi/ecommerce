<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdersRepository")
 */
class Orders
{

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
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
     * @ORM\Column(name="client", type="string", length=255)
     */


    /**
     * @var OrderProducts[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderProducts", mappedBy="order", cascade={"remove"})
     */
    private $orderProducts;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Името не може да е празно"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Емейла не може да бъде празен"
     * )
     */
    private $email;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="orders", cascade={"persist"})
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Областта не може да е празна"
     * )
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="municipality", type="string", length=255)
     * @Assert\NotBlank(
     *     message="Моля попълнете община"
     * )
     */
    private $municipality;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Моля попълнете град"
     * )
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Моля попълнете телефона си"
     * )
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Моля попълнете адреса си"
     * )
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="integer")
     *
     * @Assert\NotBlank(
     *     message="Пощенския код не може да е празен"
     * )
     */
    private $postCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetimetz", nullable=true)
     */
    private $createdOn;

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
     * Set municipality
     *
     * @param string $municipality
     *
     * @return Orders
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * Get municipality
     *
     * @return string
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Orders
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Orders
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Orders
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return Orders
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return OrderProducts[]|ArrayCollection
     */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }

    /**
     * @param OrderProducts[]|ArrayCollection $orderProducts
     */
    public function setOrderProducts($orderProducts)
    {
        $this->orderProducts = $orderProducts;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }
}

