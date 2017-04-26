<?php
/**
 * Created by PhpStorm.
 * User: phoenix
 * Date: 4/25/17
 * Time: 9:50 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class GetRoles
{

    /**
     * @var EntityManager
     */

    protected $enmanager;

    /**
     * PriceCalculatorService constructor.
     * @param EntityManager $emanager
     */

    public function __construct(EntityManager $emanager)
    {
        $this->enmanager = $emanager;
    }

    public function getRolesIdAndTitle(){
        $result = array();
        foreach ($this->getRoles() as $role) {
            $result[$role->getId()] = $role->getTitle();
        }
        return $result;
    }

    public function getRoles(){
        $query = $this->enmanager->getRepository('AppBundle:Role')->fetchAllRoles();
        return $query->getResult();
    }


}