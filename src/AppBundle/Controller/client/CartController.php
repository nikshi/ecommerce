<?php

namespace AppBundle\Controller\client;

use AppBundle\Entity\OrderProducts;
use AppBundle\Entity\Orders;
use AppBundle\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{

    private $limit_per_page = 10;


    /**
     * @Route("/orders-from-me", name="order_from_me")
     * @Method("GET")
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function ordersFromMeAction(Request $request){

        if(!$this->getUser()) {
            return $this->render("orders/orders_from_me.html.twig", array('orders' => []));
        }

        $query = $this->getDoctrine()->getRepository('AppBundle:Orders')->fetchOrdersByUser( $this->getUser()->getId() );

        $paginator  = $this->get('knp_paginator');
        $orders = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render("order/orders_from_me.html.twig", array('orders' => $orders));
    }

    /**
     * @Route("/orders-to-me", name="order_to_me")
     * @Method("GET")
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function ordersToMeAction(Request $request){

        if(!$this->getUser()) {
            return $this->render("orders/orders_from_me.html.twig", array('orders' => []));
        }

        $query = $this->getDoctrine()->getRepository('AppBundle:OrderProducts')->fetchOrdersToUser( $this->getUser() );

        $paginator  = $this->get('knp_paginator');
        $orders = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render("order/orders_to_me.html.twig", array('orders' => $orders));
    }

    /**
     * @Route("/orders-to-me/{id}", name="view_order_ship_info")
     * @Method("GET")
     * @Security(expression="has_role('ROLE_USER')")
     */
    public function orderShipInfo(Request $request, $id){
        $order = $this->getDoctrine()->getRepository('AppBundle:OrderProducts')->find($id);
        return $this->render("order/order_shipping_info.html.twig", array('order'=> $order->getOrder()));
    }

    /**
     * @Route("/cart", name="view_cart")
     * @Method("GET")
     */

    public function cartAction(){

        $order = $this->get('session')->get('order');
        $user = $this->getUser();

        $orderUser = $this->createForm(OrderType::class, $user, array(
            'action' => $this->generateUrl('checkout')
        ));

        dump($order);

        return $this->render("order/viewCart.html.twig", array('order' => $order, 'orderUser' => $orderUser->createView()));

    }


    /**
     * @Route("/CartDeleteProduct", name="cart_delete_product")
     * @Method("Post")
     */
    public function cartDeleteProduct(Request $request) {
        $order = $this->get('session')->get('order');
        unset($order['products'][$request->get('product_id')]);
        $this->get('session')->set('order', $this->calcCartInfo($order));
        $this->addFlash('success', "Продуктът е успешно изтрит");
        return $this->redirectToRoute("view_cart");
    }

    /**
     * @Route("/editProductQty", name="edit_product_qty")
     * @Method("Post")
     */
    public function editProductQty(Request $request) {

        $order      = $this->get('session')->get('order');
        $qty        = $request->get('cart_product_qty');
        $product_id = $request->get('product_id');

        if($qty <= 0) {
            unset($order['products'][$product_id]);
            $this->get('session')->set('order', $this->calcCartInfo($order));
            $this->addFlash('success', "Продуктът е успешно изтрит");
            return $this->redirectToRoute("view_cart");
        }
        $order['products'][$product_id]['qty'] = $qty;
        $this->get('session')->set('order', $this->calcCartInfo($order));
        $this->addFlash('success', "Количеството на продукта е успешно променено");
        return $this->redirectToRoute("view_cart");
    }


    /**
     * @Route("/checkout", name="checkout")
     * @Method("POST")
     */
    public function checkoutProcess(Request $request)
    {

        $session_order = $this->get('session')->get('order');

        $order = new Orders();
        $form = $this->createForm(OrderType::class, $order, array(
            'action' => $this->generateUrl('checkout')
        ));

        $form->handleRequest($request);
        if ( $this->getUser() ) {
            if (!$this->get('app.order')->haveEnoughtCash($this->getUser())) {
                $this->addFlash('error', "Съжаляваме! Нямате достатъчно наличност по сметката.");
                return $this->render("order/viewCart.html.twig", array('order' => $session_order, 'orderUser' => $form->createView()));
            }
        }

        if($form->isSubmitted() && $form->isValid()){

            $order->setUser($this->getUser());
            $order->setCreatedOn(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            $product_repo = $this->getDoctrine()->getRepository('AppBundle:Product');
            foreach ($session_order['products'] as $product) {
                if( $p = $product_repo->find($product['id']) ) {
                    if( !$this->get('app.order')->calculateProductQty($product) ) {
                        $this->addFlash('error', "Продукт " . $product['name'] . " беше изчерпан преди малко");
                        continue;
                    }
                    $this->get('app.order')->payToSeller($product);
                    if( $this->getUser() ){
                        $this->get('app.order')->takeMoneyFromClient($product, $this->getUser()->getId());
                    }

                    $orderProducts = new OrderProducts();
                    $orderProducts->setOrder($order);
                    $orderProducts->setProduct($p);
                    if($product['promo_price'] > 0){
                        $orderProducts->setPrice($product['promo_price']);
                    } else {
                        $orderProducts->setPrice($product['price']);
                    }
                    $orderProducts->setQty($product['qty']);
                    $em->persist($orderProducts);
                    $em->flush();
                }
            }

            $this->get('app.order')->clearOrder();

            $this->addFlash('success', "Вашата поръчка е приета успешно");
            return $this->redirectToRoute("view_order", array('id'=>$order->getId()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render("order/viewCart.html.twig", array('order' => $session_order, 'orderUser' => $form->createView()));
        }


    }

    /**
     * @Route("/order/addtocard", name="add_to_cart")
     * @Method("POST")
     */
    public function indexProcess(Request $request)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($request->request->get('product_id'));

        if ( $this->getUser() ){
            if ($product->getUser()->getId() == $this->getUser()->getId()) {
                $this->addFlash('error', "Не може да поръчвате Ваш продукт");
                return $this->redirectToRoute("product_by_slug", array('category' => $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
            }
        }

        if($request->request->get('order_qty') < 0){
            $this->addFlash('error', "Количеството не може да е 0 или отрицателно число");
            return $this->redirectToRoute("product_by_slug", array('category'=> $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
        }

        if($request->request->get('order_qty') > $product->getQty()){
            $this->addFlash('error', "Желаното от Вас количество не е налично");
            return $this->redirectToRoute("product_by_slug", array('category'=> $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
        }

        if(!$product){
            throw $this->createNotFoundException(
                'No Product found with id: '.$request->request->get('product_id')
            );
        }

        $this->get('app.price_calculator')->setProductPromoPrice($product);

        $order['products'][$product->getId()] = [
            'id'                => $product->getId(),
            'name'              => $product->getName(),
            'qty'               => $request->request->get('order_qty'),
            'productImage'      => $product->getProductImage(),
            'slug'              => $product->getSlug(),
            'price'             => $product->getPrice(),
            'user_id'           => $product->getUser()->getId(),
            'promo_price'       => $product->getPromoPrice(),
            'category_name'     => $product->getCategory()->getName(),
            'category_slug'     => $product->getCategory()->getSlug(),
        ];

        if($this->get('session')->has('order')){
            $_order = $this->get('session')->get('order');
            $newProduct = true;
            foreach ($_order['products'] as &$prod ){
                if($prod['id'] === $product->getId()){
                    $prod['qty'] += $request->request->get('order_qty');
                    $newProduct = false;
                    break;
                }
            }

            if($newProduct){
                $_order['products'] = $_order['products'] + $order['products'];
            }

            $this->get('session')->set('order', $this->calcCartInfo($_order));
        } else {
            $this->get('session')->set('order', $this->calcCartInfo($order));
        }
        return $this->redirectToRoute("product_by_slug", array('category'=> $product->getCategory()->getSlug(), 'slug' => $product->getSlug()));
    }

    /**
     * @Route("/order/{id}", name="view_order")
     *@Method("GET")
     */
    public function orderInfo(Request $request, $id){
        $order = $this->getDoctrine()->getRepository('AppBundle:Orders')->find($id);
        $products = $order->getOrderProducts()->getValues();
        return $this->render("order/order.html.twig", array('order'=> $order, 'products' => $products));
    }

//    Helper Functions

    private function calcCartInfo($order){
        $total_price = 0;
        foreach ($order['products'] as $p){
            if( $p['promo_price'] > 0 ) {
                $total_price += $p['promo_price'] * $p['qty'];
            } else {
                $total_price += $p['price'] * $p['qty'];
            }
        }

        $order['products_count']    = count($order['products']);
        $order['total_price']       = $total_price;

        return $order;

    }

}
