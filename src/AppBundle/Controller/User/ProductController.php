<?php

namespace AppBundle\Controller\User;

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

class ProductController extends Controller
{

    private $limit_per_page = 5;


    /**
     * @Route("/products", name="user_products")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function ProductsAction(Request $request)
    {
        $userId = $this->getUser()->getId();
        $query = $this->getDoctrine()->getRepository('AppBundle:Product')->fetchProductsByUser($userId);

        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render('products/list.html.twig', ['products'=> $products]);
    }

    /**
     * @Route("/products/edit/{id}", name="edit_product")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function editAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        $product->setCategory($product->getCategory()->getId());

        if (!$product) {
            throw $this->createNotFoundException(
                'No user found with id: '.$product
            );
        }

        $promotion = $em->getRepository('AppBundle:Promotion')->findOneBy(['product'=>$product]);

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion, array(
            'action' => $this->generateUrl('save_product_promotion', array('id' => $product->getId())
        )));

        $form = $this->createForm(ProductType::class, $product, array(
            'categories' => $this->getProductsCategories()
        ) );
        return $this->render('products/edit.html.twig', ['form'=> $form->createView(), 'product' => $product, 'promotionForm'=> $promotionForm->createView() ]);
    }

    /**
     * @Route("/products/edit/{id}")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function editProcess(Request $request, $id = null)
    {

        $em             = $this->getDoctrine();
        $product        = $em->getRepository('AppBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found with id: '.$id
            );
        }

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($request->request->get('product')['category']);
        $product->setCategory($category->getId());

        $form = $this->createForm(ProductType::class, $product, array(
            'categories' => $this->getProductsCategories()
        ));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            if($product->getImageForm() instanceof UploadedFile) {

                $filePath = $this->get('kernel')->getRootDir() . '/../web/images/products/' . $product->getId();

                /** @var UploadedFile $file */
                $file = $product->getImageForm();
                $filename = md5($product->getName() . '' . $product->getCreatedOn()->format('Y-m-d H:i:s')) . '.' . $file->getClientOriginalExtension();
                foreach(glob($filePath.'*.*') as $v){
                    unlink($v);
                }
                $file->move($filePath, $filename);
                $product->setProductImage($filename);

            } else {
                $product->setProductImage($product->getProductImage());
            }

            $product->setUpdatedOn(new \DateTime());
            $product->setCategory($category);
            $em = $em->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е успешно редактиран");

            return $this->redirectToRoute("edit_product", array('id'=>$product->getId()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('products/edit.html.twig', ['form'=> $form->createView()]);
        }
    }


    /**
     * @Route("admin/products/delete", name="delete_product")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function deleteProcess(Request $request)
    {

        $em     = $this->getDoctrine();
        $product   = $em->getRepository('AppBundle:Product')->find($request->get('id'));

        if($product){
            $fs = new Filesystem();

            $imageDir = $this->get('kernel')->getRootDir().'/../web/images/products/'.$product->getId();
            $fs->remove($imageDir);

            $em = $em->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е успешно изтрит");
            return $this->redirectToRoute("user_products");
        }else {
            $this->addFlash('error', "Грешка! Продуктът не е намерен");
            return $this->redirectToRoute("user_products");
        }
    }

    /**
     * @Route("/products/addproduct", name="add_product")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */

    public function addProductAction()
    {
        $form = $this->createForm(ProductType::class, null, array(
            'categories' => $this->getProductsCategories()
        ));
        return $this->render('products/addproduct.html.twig', ['form'=> $form->createView()]);
    }

    /**
     * @Route("/products/addproduct")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_USER')")
     */
    public function addProductProcess(Request $request)
    {

        $product = new Product();
        $slugify = new Slugify();

        $form = $this->createForm(ProductType::class, $product, array(
            'categories' => $this->getProductsCategories() ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($request->request->get('product')['category']);

            $product->setCategory($category);
            $product->setUser($this->getUser());
            $slug = $slugify->slugify($product->getName(), '-');

            $product->setSlug(date("now"));
            $product->setCreatedOn(new \DateTime());
            $product->setUpdatedOn(new \DateTime());

            /** @var UploadedFile $file */
            $file = $product->getImageForm();

            if ($file){
                $filename = md5($product->getName() . '' . $product->getCreatedOn()->format('Y-m-d H:i:s')) . '.' . $file->getClientOriginalExtension();
                $product->setProductImage($filename);
            } else {
                $this->addFlash('error', "Изберете изображение за продукта");
                return $this->render('products/addproduct.html.twig', ['form'=> $form->createView()]);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $file->move($this->get('kernel')->getRootDir() . '/../web/images/products/' . $product->getId(), $filename);

            $product->setSlug($slug . '-' . $product->getId());
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е добавен успешно (" . $product->getId() . ")");

            return $this->redirectToRoute('user_products');
        }else {
            return $this->render('products/addproduct.html.twig', ['form'=> $form->createView()]);
        }
    }

//    TODO Create service
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
