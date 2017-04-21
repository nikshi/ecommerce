<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{

    public function __construct()
    {
        $this->createdOn    = new \DateTime();
        $this->categories   = new ArrayCollection();
        $this->reviews      = new ArrayCollection();
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
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products")
     *
     */
    private $user;


    /**
     * @var Review[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="product")
     */
    private $reviews;


    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinTable(name="prod_cats", joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},  inverseJoinColumns={@ORM\JoinColumn(name="cat_id", referencedColumnName="id") })")
     *
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=true)
     */

    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=true)
     */
    private $qty;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetimetz", nullable=true)
     */
    private $created_on;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetimetz", nullable=true)
     */
    private $updated_on;

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
     * @return Product
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return Product
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param \DateTime $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }

    /**
     * @param \DateTime $updated_on
     */
    public function setUpdatedOn($updated_on)
    {
        $this->updated_on = $updated_on;
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
     * @return Review[]|ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review[]|ArrayCollection $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

}

