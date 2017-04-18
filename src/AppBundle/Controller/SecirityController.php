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


class SecirityController extends Controller
{


    /**
     * @Route("/register", name="user_register")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction()
    {
        $form = $this->createForm(UserType::class);
        return $this->render('security/register.html.twig', ['form'=> $form->createView()]);
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

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $encoder = $this->get('security.password_encoder');

        if($form->isValid()){
            $hashedPassword = $encoder->encodePassword($user, $user->getPassword());
            $userRole = $this->getDoctrine()->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($userRole);
            $user->setPassword($hashedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("homepage");
        }else {
            return $this->render('security/register.html.twig', ['form'=> $form->createView()]);
        }
    }


    /**
     * @Route("/login", name="user_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $user =  $this->getUser();

        echo '<pre><br>=====DEBUG START======<br> '.print_r($user,1).' <br>=====DEBUG END======<br></pre>';

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('security/login.html.twig');
    }


    /**
     * @Route("/admin", name="admin")
     * @Security(expression="has_role('ROLE_USER')")
     */
    public function adminAction()
    {
        echo "GREATTT !!!!";
    }

}
