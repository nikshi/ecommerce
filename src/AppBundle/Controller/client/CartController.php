<?php

namespace AppBundle\Controller\client;

use AppBundle\Entity\Orders;
use AppBundle\Form\OrderType;
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

        $orderUser = $this->createForm(OrderType::class, $user, array(
            'action' => $this->generateUrl('checkout')
        ));

        return $this->render("order/viewCart.html.twig", array('products' => $products, 'orderUser' => $orderUser->createView()));

    }

    /**
     * @Route("/checkout", name="checkout")
     * @Method("POST")
     */
    public function checkoutProcess(Request $request){

        $products = $this->get('app.cart')->getCartMini();
//        $user = $this->getUser();
//        $cart_products = $request->get('products');
//        $cart_user = $request->get('user');
//        dump($cart_products);
//        dump($cart_user);

        $order = new Orders();

        $form = $this->createForm(OrderType::class, $order, array(
            'action' => $this->generateUrl('checkout')
        ));

        $form->handleRequest($request);

        dump($form);

        if($form->isSubmitted() && $form->isValid()){

//            $form->setProduct($product);
//            $form->setCreatedOn(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($form);
            $em->flush();

            $this->addFlash('success', "Ревюто е добавено успешно");

            return $this->render("order/viewCart.html.twig", array('products' => $products, 'orderUser' => $form->createView()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render("order/viewCart.html.twig", array('products' => $products, 'orderUser' => $form->createView()));
        }


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
