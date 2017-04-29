<?php

namespace AppBundle\Repository;

/**
 * OrderProductsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderProductsRepository extends \Doctrine\ORM\EntityRepository
{

    public function fetchOrdersToUser($user){

        $qb = $this->createQueryBuilder('a');

        return $qb->select('a')
            ->innerJoin('a.product', 'p')
            ->where($qb->expr()->eq('p.user', ':user') )
            ->setParameter(':user', $user)
            ->orderBy('a.id', 'DESC')
            ->getQuery();
    }

}