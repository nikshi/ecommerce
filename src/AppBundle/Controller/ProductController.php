<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{

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
        return $this->render('admin/addproduct.html.twing', ['form'=> $form->createView()]);
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


        $form = $this->createForm(ProductType::class, $product, array(
            'categories' => $this->getProductsCategories() ));

        dump($request);exit;

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){

//            $product->setCreatedOn( new \DateTime());
//            $product->setUpdatedOn( new \DateTime());

//            /** @var UploadedFile $file */
//            $file = $product->getImage();
//
//            $filename = md5($product->getName().''.$product->getCreatedOn()->format('Y-m-d H:i:s')).'.'.$file->getExtension();
//
//            $file->move($this->get('kernel')->getRootDir().'/../web/images/products/'.$product->getId(), $filename);
//
//            $product->setImage($filename);

//            $em = $this->getDoctrine()->getManager();
//            $em->persist($product);
//            $em->flush();

//            $this->addFlash('success', "Продуктът е добавен успешно");

            return $this->redirectToRoute("user_login");
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
