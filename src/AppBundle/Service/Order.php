<?php
/**
 * Created by PhpStorm.
 * User: phoenix
 * Date: 4/25/17
 * Time: 9:50 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class Order
{

    /**
     * @var Session
     */
    protected $session;
    protected $emanager;
    /**
     * PriceCalculatorService constructor.
     * @param Session $session
     */

    public function __construct(Session $session, EntityManager $emanager)
    {
        $this->session      = $session;
        $this->emanager    = $emanager;
    }


    /**
     * @param $order
     * @param User $user
     * @return bool
     */
    public function haveEnoughtCash(User $user) {
        $order = $this->session->get('order');
        if($user->getCash() < $order['total_price']) {
            return false;
        }
        return true;
    }


    public function getOrder(){
        $order = [];
        if( $this->session->has('order') ){
            $order = $this->session->get('order');
        }
        return $order;
    }


    public function payToSeller($product){
        $user = $this->emanager->getRepository('AppBundle:User')->find($product['user_id']);
        $user_cash = $user->getCash();
        if($product['promo_price'] > 0 ){
            $user->setCash( $user_cash + $product['qty'] * $product['promo_price'] );
        } else {
            $user->setCash( $user_cash + $product['qty'] * $product['price'] );
        }
        $this->emanager->persist($user);
        $this->emanager->flush();

        return true;
    }

    public function calculateProductQty($prod){

        $product = $this->emanager->getRepository('AppBundle:Product')->find($prod['id']);
        $new_qty = $product->getQty() - $prod['qty'];
        if($new_qty <= 0) {
            return false;
        }
        $product->setQty( $new_qty );
        $this->emanager->persist($product);
        $this->emanager->flush();
        return true;
    }

    public function takeMoneyFromClient($product, $client_id){

        $user = $this->emanager->getRepository('AppBundle:User')->find($client_id);
        $user_cash = $user->getCash();
        if($product['promo_price'] > 0){
            $user->setCash( $user_cash - $product['promo_price'] * $product['qty'] );
        } else {
            $user->setCash( $user_cash - $product['price'] * $product['qty'] );
        }
        $this->emanager->persist($user);
        $this->emanager->flush();

        return true;

    }

    public function clearOrder(){
        $this->session->remove('order');
    }

}