<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Category;

/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromotionRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     *
     * @param Category $category
     * @return int
     */

    public function fetchBiggestPromotion(){

        $qb = $this->createQueryBuilder('p');
        $today = new \DateTime();

        $query = $qb->select('p')
            ->where($qb->expr()->lte('p.startDate', ':today' ))
            ->andWhere($qb->expr()->gte('p.endDate', ':today' ))
            ->andWhere($qb->expr()->isNull('p.category'))
            ->andWhere($qb->expr()->isNull('p.product'))
            ->setParameter(':today', $today)
            ->orderBy('p.percent', 'DESC')
            ->getQuery();



        return $query->getSingleResult();

    }

}