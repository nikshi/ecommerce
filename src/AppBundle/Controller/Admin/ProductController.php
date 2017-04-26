<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\Review;
use AppBundle\Form\ProductPromotionType;
use AppBundle\Form\ProductType;
use AppBundle\Form\ReviewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

use Symfony\Component\Filesystem\Filesystem;


/**
 * @Route("/admin")
 */
class ProductController extends \AppBundle\Controller\User\ProductController
{

    private $limit_per_page = 10;


    /**
     * @Route("/products/all", name="editor_products")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_EDITOR')")
     */

    public function ProductsAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('AppBundle:Product')->fetchAllProducts();

        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render('products/list.html.twig', ['products'=> $products]);
    }


}
