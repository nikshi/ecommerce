<?php

namespace AppBundle\Repository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends \Doctrine\ORM\EntityRepository
{

    public function fetchAllRoles(){
        $qb = $this->createQueryBuilder('a');
        return $qb->select('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();
    }

}
