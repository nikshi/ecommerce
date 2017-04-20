<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
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

    /**
     * @Route("/")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function profileAction()
    {
        return $this->render('user/profile.html.twig', array());
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

        if($form->isValid()){
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
