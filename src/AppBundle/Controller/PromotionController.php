<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promotion;
use AppBundle\Form\ProductPromotionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }


    /**
     * @param Request $request
     * @Route("promotion/product/{id}/save", name="save_product_promotion")
     */
    public function saveProductPromotion(Request $request, $id)
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
}
