<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/edit")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function editAction()
    {
        return $this->render('user/edit.html.twig', array());
    }
}
