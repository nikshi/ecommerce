<?php

namespace AppBundle\Controller;

use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Route("/admin", name="dashboard")
 */

class AdminController extends Controller
{

    /**
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function indexAction()
    {
        return $this->render('admin/dashboard.html.twing', array());
    }



}
