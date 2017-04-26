<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\ProductType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 * @Security(expression="has_role('ROLE_USER')")
 *
 */

class UserController extends Controller
{

    protected $limit_per_page = 2;

    /**
     * @Route("admin/users", name="list_users")
     * @Method("GET")
     */

    public function profileAction(Request $request)
    {

        $query = $this->getDoctrine()->getRepository('AppBundle:User')->fetchAllUsers();

        $paginator  = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render('user/listusers.html.twig', array( 'users' => $users ));
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);


        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id: '.$user
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        return $this->render('user/edit.html.twig', ['form'=> $form->createView()]);
    }


    /**
     * @Route("/edit/{id}", name="user_edit_process")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUserProcess(Request $request, $id)
    {
        $em     = $this->getDoctrine();
        $user   = $em->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id: '.$user
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $em->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Профилът Ви е успешно записан!");

            return $this->redirectToRoute("user_login");
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('user/edit.html.twig', ['form'=> $form->createView()]);
        }
    }

}
