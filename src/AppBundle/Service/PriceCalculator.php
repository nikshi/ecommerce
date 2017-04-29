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

class PriceCalculator
{

    /**
     * @var EntityManager
     */

    protected $enmanager;

    protected $promotion;

    protected $category_promotions = [];

    /**
     * PriceCalculatorService constructor.
     * @param EntityManager $emanager
     */

    public function __construct(EntityManager $emanager)
    {
        $this->enmanager = $emanager;
//        $this->promotion = $this->initMaxPromotion();
    }


    public function setProductPromoPrice(Product $product){
        $this->calculateProductPrice($product);
    }

    public function setProductsPromoPrice($products){
        foreach ($products as $product){
            $this->calculateProductPrice($product);
        }
    }

    private function calculateProductPrice( Product $product ){


        $today = new \DateTime();

        $biggestPercent = 0;

        $product_promotions             = $product->getPromotions()->getValues();

        foreach ($product_promotions as $promotion){
            if($promotion->getPercent() > $biggestPercent) $biggestPercent = $promotion->getPercent();
        }
        $product_category_promotions    = $product->getCategory()->getPromotions()->getValues();
        foreach ($product_category_promotions as $promotion){
            if($promotion->getPercent() > $biggestPercent) $biggestPercent = $promotion->getPercent();
        }

        $all_products_biggest_promo     = $this->enmanager->getRepository('AppBundle:Promotion')->fetchBiggestPromotion();
        if( $all_products_biggest_promo ) {
            if($all_products_biggest_promo->getPercent() > $biggestPercent) $biggestPercent = $all_products_biggest_promo->getPercent();
        }

        if( $biggestPercent > 0 ) {
            $promoPrice = $product->getPrice() - $product->getPrice() * ($biggestPercent / 100);
            $product->setPromoPrice($promoPrice);
        }
    }




    /**
     * @param Product $product
     * @return float
     */

    public function calculate($product){

        $category = $product->getCategory();

        if(!isset($this->category_promotions[$category->getId()])){
            $this->category_promotions[$category->getId()] =
                $this->enmanager
                    ->getRepository('AppBundle:Promotion')
                    ->fetchBiggestPromotion($category);
        }

        $promotion = $this->category_promotions[$category->getId()];

        if($promotion === 0 && $this->promotion == null){
            $this->promotion = $this->enmanager
                ->getRepository('AppBundle:Promotion')
                ->fetchBiggestPromotion();
        }
        $promotion = $this->promotion;

        return $product->getPrice() - $product->getPrice() * ($promotion / 100);

    }

}