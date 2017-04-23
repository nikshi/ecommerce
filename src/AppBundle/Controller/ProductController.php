<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

class ProductController extends Controller
{


    /**
     * @Route("/user/products", name="user_products")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function ProductsAction(Request $request)
    {

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AppBundle:Product a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

//        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(['user' => $this->getUser()]);

        return $this->render('products/list.html.twig', ['products'=> $products]);
    }

    /**
     * @Route("/user/products/addproduct", name="add_product")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addProductAction()
    {

        $form = $this->createForm(ProductType::class, null, array(
            'categories' => $this->getProductsCategories()
        ));
        return $this->render('products/addproduct.html.twig', ['form'=> $form->createView()]);
    }

    /**
     * @Route("/user/products/addproduct")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProductProcess(Request $request)
    {

        $product = new Product();
        $slugify = new Slugify();

        $form = $this->createForm(ProductType::class, $product, array(
            'categories' => $this->getProductsCategories() ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($request->request->get('product')['category']);

            $product->setCategory($category);
            $product->setUser($this->getUser());
            $slug =  $slugify->slugify( $product->getName(), '-');

            $product->setSlug( date("now") );
            $product->setCreatedOn( new \DateTime());
            $product->setUpdatedOn( new \DateTime());

            /** @var UploadedFile $file */
            $file = $product->getProductImage();
            $filename = md5($product->getName().''.$product->getCreatedOn()->format('Y-m-d H:i:s')).'.'.$file->getClientOriginalExtension();
            $product->setProductImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $file->move($this->get('kernel')->getRootDir().'/../web/images/products/'.$product->getId(), $filename);
            $product->setSlug( $slug . '-' . $product->getId() );

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е добавен успешно (" . $product->getId() . ")");

            return $this->redirectToRoute('user_products');
        }else {
            return $this->render('security/register.html.twig', ['form'=> $form->createView()]);
        }
    }


//    Helper methods


    public function getProductsCategories(){

        $em     = $this->getDoctrine();
        $_categories   = $em->getRepository('AppBundle:Category')->findAll();

        if(!$_categories) return [];

        foreach ($_categories as $category){
            $categories[$category->getId()] = $category->getName();
        }

        return $categories;
    }

}
