<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class AdminController
 * @package AppBundle\Controller
 *
 * @Security(expression="has_role('ROLE_USER')")
 */

class AdminController extends Controller
{

    /**
     * @Route("/admin", name="dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function indexAction()
    {
        return $this->render('admin/dashboard.html.twing', array());
    }
}
