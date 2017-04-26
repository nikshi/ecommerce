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

class GetCategories
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



    public function getCategoriesWithSlugs(){
        $result = array();
        foreach ($this->getCategories() as $category) {
            $result[$category->getSlug()] = $category->getName();
        }
        return $result;
    }


    public function getCategories(){
        $query = $this->enmanager->getRepository('AppBundle:Category')->fetchAllCategories();
        return $query->getResult();
    }


}