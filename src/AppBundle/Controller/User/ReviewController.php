<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Review;
use AppBundle\Form\ReviewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends Controller
{
    /**
     * @Route("/reviews/product/{id}/save", name="save_product_review")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function SendReviewProcess(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);

        $review = new Review();

        $reviewForm = $this->createForm(ReviewsType::class, $review, array());

        $reviewForm->handleRequest($request);
        $price_calculator = $this->get('app.price_calculator');
        $price_calculator->setProductPromoPrice($product);
        if($reviewForm->isSubmitted() && $reviewForm->isValid()){

            $review->setProduct($product);
            $review->setCreatedOn(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            $this->addFlash('success', "Ревюто е добавено успешно");

            return $this->redirectToRoute("product_by_slug", array('category' => $product->getCategory()->getSlug(), 'slug'=>$product->getSlug()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('products/singleProduct.html.twig', ['product'=> $product, 'reviewForm' => $reviewForm->createView()]);
        }
    }
}
