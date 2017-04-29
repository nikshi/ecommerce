<?php

namespace AppBundle\Controller\client;

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

class ProductController extends Controller
{

    private $limit_per_page = 3;

    /**
     * @Route("/{categorySlug}", name="products_by_cat_slug")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function ProductsByCatSlugAction(Request $request, $categorySlug)
    {

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBy(['slug' => $categorySlug]);
        $query = $this->getDoctrine()->getRepository('AppBundle:Product')->fetchProductsByCat($category);

        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        $price_calculator = $this->get('app.price_calculator');
        $price_calculator->setProductsPromoPrice($products);

        return $this->render('products/blocks.html.twig', ['products'=> $products, 'category' => $category]);
    }

    /**
     * @Route("/{category}/{slug}", name="product_by_slug")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function ProductBySlugAction($slug = null)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(['slug' => $slug]);

        $reviewForm = $this->createForm(ReviewsType::class, null, array(
            'action' => $this->generateUrl('save_product_review', array('id' => $product->getId())
            )));

        $price_calculator = $this->get('app.price_calculator');
        $price_calculator->setProductPromoPrice($product);

        return $this->render('products/singleProduct.html.twig', ['product'=> $product, 'reviewForm' => $reviewForm->createView()]);
    }

}
