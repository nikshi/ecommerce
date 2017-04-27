<?php

namespace AppBundle\Controller\client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{

    /**
     * @Route("/cart", name="view_cart")
     * @Method("GET")
     */

    public function cartAction(){

        $products = $this->get('app.cart')->getCartMini();
        $user = $this->getUser();

        return $this->render("order/viewCart.html.twig", array('products' => $products, 'user' => $user));

    }

    /**
     * @Route("/checkout", name="checkout")
     * @Method("POST")
     */
    public function checkoutProcess(Request $request){

        $products = $this->get('app.cart')->getCartMini();
        $user = $this->getUser();

        $cart_products = $request->get('products');
        $cart_user = $request->get('user');

        foreach ($cart_user as $user_field){
            if($user_field == '') {
                dump($user_field);

                $this->addFlash('error', "Моля попълнете всички полета за доставка");
                return $this->render("order/viewCart.html.twig", array('products' => $products, 'user' => $user));
            }
        }
        dump($cart_products);
        dump($cart_user);


        return $this->render("order/viewCart.html.twig", array('products' => $products, 'user' => $user));
    }

    /**
     * @Route("/order/addtocard", name="add_to_cart")
     * @Method("POST")
     */
    public function indexProcess(Request $request)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($request->request->get('product_id'));

        if($request->request->get('order_qty') < 0){
            $this->addFlash('error', "Количеството не може да е 0 или отрицателно число");
            return $this->redirectToRoute("product_by_slug", array('category'=> $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
        }

        if(!$product){
            throw $this->createNotFoundException(
                'No Product found with id: '.$request->request->get('product_id')
            );
        }

        $order[] = [
            'product_id'    => $product->getId(),
            'product_qty'   => $request->request->get('order_qty')
        ];

        if($this->get('session')->has('order')){
            $_order = $this->get('session')->get('order');
            $newProduct = true;
            foreach ($_order as &$prod ){
                if($prod['product_id'] === $product->getId()){
                    $prod['product_qty'] += $request->request->get('order_qty');
                    $newProduct = false;
                    break;
                }
            }
            if($newProduct){
                $_order = array_merge($_order, $order);
            }
            $this->get('session')->set('order', $_order);
        } else {
            $this->get('session')->set('order', $order);
        }
        return $this->redirectToRoute("product_by_slug", array('category'=> $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
    }
}
