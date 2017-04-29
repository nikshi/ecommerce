<?php
/**
 * Created by PhpStorm.
 * User: phoenix
 * Date: 4/25/17
 * Time: 9:50 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class Products
{

    /**
     * @var EntityManager
     */

    protected $enmanager;

    /**
     * PriceCalculatorService constructor.
     * @param EntityManager $emanager
     */

    public function __construct(EntityManager $emanager)
    {
        $this->enmanager = $emanager;
    }

    public function getRandomProducts($number_of_products){
        $query = $this->enmanager->getRepository('AppBundle:Product')->fetchRandomProducts($number_of_products);
        return $query->getResult();
    }


}