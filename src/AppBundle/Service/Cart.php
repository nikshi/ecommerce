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
use Symfony\Component\HttpFoundation\Session\Session;

class Cart
{

    /**
     * @var EntityManager
     */

    protected $enmanager;
    protected $session;
    protected $priceCalculator;

    /**
     * PriceCalculatorService constructor.
     * @param EntityManager $emanager
     */

    public function __construct(EntityManager $emanager, Session $session, PriceCalculator $priceCalculator)
    {
        $this->enmanager        = $emanager;
        $this->session          = $session;
        $this->priceCalculator  = $priceCalculator;
    }

    public function getCartMini(){
        $result = [];
        foreach ($this->getCart() as $key => $prod) {
            $result[$key]['qty']            = $prod['qty'];
            $result[$key]['id']             = $key;
            $result[$key]['name']           = $prod['product']->getName();
            $result[$key]['productImage']   = $prod['product']->getProductImage();
            $result[$key]['slug']           = $prod['product']->getSlug();
            $result[$key]['price']          = $prod['product']->getPrice();
            $result[$key]['promo_price']    = $prod['product']->getPromoPrice();
            $result[$key]['category_name']  = $prod['product']->getCategory()->getName();
            $result[$key]['category_slug']  = $prod['product']->getCategory()->getSlug();
        }
        return $result;
    }

    public function getCart(){
        $result = array();
        if(!$this->session->has('order') || count($order = $this->session->get('order')) <= 0){
            return $result;
        }
        foreach ($order as $prod) {
            $product = $this->enmanager->getRepository('AppBundle:Product')->find($prod['product_id']);
            $this->priceCalculator->setProductPromoPrice($product);
            if($product){
                $result[$product->getId()] = ['product' => $product, 'qty'=>$prod['product_qty']];
            }
        }

        return $result;
    }


}