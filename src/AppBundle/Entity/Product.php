<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
        $this->createdOn     = new \DateTime();
        $this->reviews       = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
        $this->promotions    = new ArrayCollection();
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products", cascade={"persist"})
     */
    private $user;


    /**
     * @var OrderProducts[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderProducts", mappedBy="order")
     */
    private $orderProducts;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products", cascade={"persist"})
     *
     * @Assert\NotNull(
     *     message="Изберете категория"
     * )
     */
    private $category;

    /**
     * @var Review[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="product", cascade={"remove"})
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     *
     * @Assert\NotBlank(
     *     message="Въведете име на продукта!"
     * )
     *
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
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     *
     * @Assert\NotBlank(
     *     message="Въведете цена на продукта"
     * )
     */

    private $price;


    /**
     * @var float
     */
    private $promoPrice = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=true)
     */
    private $qty;

    /**
     * @var string
     *
     * @ORM\Column(name="product_image", type="string", nullable=true)
     */
    private $productImage;

    /**
     * @var int
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @var string
     */

    private $image_form;


    /**
     * @var Promotion[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Promotion", mappedBy="product", cascade={"remove"})
     */
    private $promotions;

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
     * @return string
     */
    public function getProductImage()
    {
        return $this->productImage;
    }

    /**
     * @param string $productImage
     */
    public function setProductImage($productImage)
    {
        $this->productImage = $productImage;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getImageForm()
    {
        return $this->image_form;
    }

    /**
     * @param mixed $image_form
     */
    public function setImageForm($image_form)
    {
        $this->image_form = $image_form;
    }

    /**
     * @return Promotion[]|ArrayCollection
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param Promotion[]|ArrayCollection $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }

    /**
     * @return float
     */
    public function getPromoPrice()
    {
        return $this->promoPrice;
    }

    /**
     * @param float $promoPrice
     */
    public function setPromoPrice($promoPrice)
    {
        $this->promoPrice = $promoPrice;
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
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

}

