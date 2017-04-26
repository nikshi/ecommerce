<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends Controller
{

    /**
     * @Route("/register", name="user_register")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction()
    {
        $form = $this->createForm(UserType::class, null, array(
            'roles' => $this->get('app.get_roles')->getRolesIdAndTitle()
        ));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form->remove('role');
            return $this->render('security/register.html.twig', ['form'=> $form->createView()]);
        } else {
            return $this->render('user/adduser.html.twig', ['form'=> $form->createView()]);
        }

    }

    /**
     * @Route("/register", name="user_register_process")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerProcess(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,array(
            'roles' => $this->get('app.get_roles')->getRolesIdAndTitle()
        ));
        $form->handleRequest($request);

        $encoder = $this->get('security.password_encoder');

        if($form->isSubmitted() && $form->isValid()){
            $user->setCreatedOn(new \DateTime());
            $hashedPassword = $encoder->encodePassword($user, $user->getPassword());

            $userRole = $this->getDoctrine()->getRepository('AppBundle:Role')
                ->findOneBy(['name' => 'ROLE_USER']);

            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $userRole = $this->getDoctrine()->getRepository('AppBundle:Role')
                    ->find($request->request->get('user')['role']);
            }

            $user->setRole($userRole);
            $user->setPassword($hashedPassword);
            $user->setCash(1000);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Успешна регистрация! Може да влезнете в профила си.");

            return $this->redirectToRoute("user_login");
        }else {
            return $this->render('security/register.html.twig', ['form'=> $form->createView()]);
        }
    }


    /**
     * @Route("/login", name="user_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('security/login.html.twig');
    }


    /**
     * @Route("user/changepassword/{id}", name="change_password_user")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function changePasswordAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id: '.$user
            );
        }

        $form = $this->createForm(ChangePasswordType::class, $user);

        return $this->render('user/changepassword.html.twig', ['form'=> $form->createView()]);
    }

    /**
     * @Route("user/changepassword/{id}")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function changePasswordProcess(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id: '.$user
            );
        }

        $userPassword = $user->getPassword();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        $encoder = $this->get('security.password_encoder');

        if($form->isSubmitted() && $form->isValid()){

            $old_pwd = $request->get('change_password')['oldPassword'];

            $hashedPasswordOldPass = $encoder->encodePassword($user, '1234');

            dump($request->get('change_password')['oldPassword']);
            dump($hashedPasswordOldPass);
            dump($userPassword);

            if( $hashedPasswordOldPass != $userPassword ) {

                $this->addFlash('error', "Въвели сте грешна текуща парола");
                return $this->render('user/changepassword.html.twig', ['form'=> $form->createView()]);
            } else {
                //            $hashedPassword = $encoder->encodePassword($user, $user->getPassword());
//
//            $user->setPassword($hashedPassword);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
                $this->addFlash('success', "Успешна регистрация! Може да влезнете в профила си.");
            }

            return $this->redirectToRoute("user_products");
        }else {
            return $this->render('user/changepassword.html.twig', ['form'=> $form->createView()]);
        }


    }

}
