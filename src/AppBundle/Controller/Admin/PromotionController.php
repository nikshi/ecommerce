<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Promotion;
use AppBundle\Form\ProductPromotionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends Controller
{

    /**
     * @Route("/admin/promotion/addglobal", name="add_global_promotion")
     * @Method("GET")
     */
    public function globalPromotionAction()
    {
        $promotion = $this->getDoctrine()
            ->getManager()->getRepository('AppBundle:Promotion')
            ->findOneBy(['category' => null, 'product' => null]);

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion);

        return $this->render('promotions/addglobalpromotion.html.twig', array('promotionForm' => $promotionForm->createView()));
    }

    /**
     * @Route("/admin/promotion/addglobal")
     * @Method("POST")
     */
    public function saveGlobalPromotionProcess(Request $request)
    {
        $promotion = $this->getDoctrine()
            ->getRepository('AppBundle:Promotion')
            ->findOneBy(['category' => null, 'product' => null]);

        if(!$promotion){
            $promotion = new Promotion();
        }

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion);
        $promotionForm->handleRequest($request);

        if($promotionForm->isSubmitted() && $promotionForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            $this->addFlash('success', "Успешно записахте глобалната промоция");
            return $this->redirectToRoute('add_global_promotion');
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('promotions/addglobalpromotion.html.twig', array('promotionForm' => $promotionForm->createView()));
        }
    }


    /**
     * @Route("promotion/product/{id}/save", name="save_product_promotion")
     * @Method("POST")
     */
    public function saveProductPromotionProcess(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $promotion = $this->getDoctrine()->getRepository('AppBundle:Promotion')->findOneBy(['product' => $id]);

        if(!$promotion){
            $promotion = new Promotion();
        }

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion);
        $promotionForm->handleRequest($request);

        if($promotionForm->isSubmitted() && $promotionForm->isValid()){

            $promotion->setProduct($product);
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', "Успешно добавихте промоция на:" . $product->getName());
            return $this->redirectToRoute('edit_product', array('id' => $product->getId()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('products/edit.html.twig', ['promotionForm' => $promotionForm->createView(), 'product' => $product]);
        }
    }

    /**
     * @Route("promotion/category/{id}/save", name="save_category_promotion")
     * @Method("POST")
     */
    public function saveCategoryPromotionProcess(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $promotion = $this->getDoctrine()->getRepository('AppBundle:Promotion')->findOneBy(['product' => $id]);

        if(!$promotion){
            $promotion = new Promotion();
        }

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion);
        $promotionForm->handleRequest($request);

        if($promotionForm->isSubmitted() && $promotionForm->isValid()){

            $promotion->setCategory($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', "Успешно добавихте промоция на:" . $category->getName());
            return $this->redirectToRoute('edit_category', array('id' => $category->getId()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('products/edit.html.twig', ['promotionForm' => $promotionForm->createView(), 'product' => $category]);
        }
    }

}
