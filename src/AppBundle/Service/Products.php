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
    protected  $priceCalculator;

    /**
     * PriceCalculatorService constructor.
     * @param EntityManager $emanager
     */

    public function __construct(EntityManager $emanager, PriceCalculator $priceCalculator)
    {
        $this->enmanager        = $emanager;
        $this->priceCalculator  = $priceCalculator;
    }

    public function getRandomProducts($number_of_products){
        $query = $this->enmanager->getRepository('AppBundle:Product')->fetchRandomProducts($number_of_products);
        return $query->getResult();
    }

    public function getRandomPromoProducts($number_of_products){
        $query = $this->enmanager->getRepository('AppBundle:Promotion')->fetchRandomPromoProducts($number_of_products);
        $result =  $query->getResult();

        foreach ($result as &$promotion){
            $this->priceCalculator->setProductPromoPrice($promotion->getProduct());
        }

        dump($result);

        return $result;

    }


}