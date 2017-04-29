<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\AdminUserType;
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

    protected $limit_per_page = 10;

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

        $user->setRole($user->getRole()->getId());
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id: '.$user
            );
        }

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(UserType::class, $user);
            $form->remove('password');
            return $this->render('user/edit.html.twig', ['form'=> $form->createView()]);
        } else {
            $form = $this->createForm(AdminUserType::class, $user, array(
                'roles' => $this->get('app.get_roles')->getRolesIdAndTitle()
            ));
            $form->remove('password');
            return $this->render('user/edit_admin.html.twig', ['form'=> $form->createView()]);
        }

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

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(UserType::class, $user);
        } else {

            $role = $this->getDoctrine()->getRepository('AppBundle:Role')->find($request->request->get('admin_user')['role']);

            $user->setRole($role->getId());
            $form = $this->createForm(AdminUserType::class, $user, array(
                'roles' => $this->get('app.get_roles')->getRolesIdAndTitle()
            ));
        }

        $form->remove('password');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $user->setRole($role);
            }
            $em = $em->getManager();
            $em->persist($user);
            $em->flush();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $this->addFlash('success', "Профилът Ви е успешно записан!");
                return $this->redirectToRoute("user_login");
            } else {
                $this->addFlash('success', "Успешно редактиране на профил: " .$user->getName() );
                return $this->redirectToRoute('list_users');
            }

        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('user/edit.html.twig', ['form'=> $form->createView()]);
        }
    }


    /**
     * @Route("admin/user/delete", name="delete_user")
     * @Method("POST")
     * @Security(expression="has_role('ROLE_ADMIN')")
     */

    public function deleteProcess(Request $request)
    {
        $em     = $this->getDoctrine();
        $user   = $em->getRepository('AppBundle:User')->find($request->get('id'));

        if($user){
            $em = $em->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "Потребителят е успешно изтрит");
            return $this->redirectToRoute("list_users");
        }else {
            $this->addFlash('error', "Грешка! Потребителя не е намерен");
            return $this->redirectToRoute("list_users");
        }
    }

}
