<?php

namespace App\ORM\Repository;

use App\ORM\Entity\OyakoCinemaTitle;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * OyakoCinemaTitle repository class
 */
class OyakoCinemaTitleRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('oct');
        $qb
            ->where('oct.isDeleted = false');

        return $qb;
    }

    /**
     * find by active
     *
     * @return OyakoCinemaTitle[]
     */
    public function findByActive()
    {
        $qb = $this->getActiveQuery();

        /**
         * あくまでOyakoCinemaTitleの条件
         * OyakoCinemaScheduleのフィルタは下記を参照
         *
         * @see App\ORM\Entity\OyakoCinemaTitle::getOyakoCinemaSchedules()
         */
        $qb
            ->join('oct.oyakoCinemaSchedules', 'ocs')
            ->andWhere('ocs.date >= CURRENT_DATE()')
            ->orderBy('ocs.date', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
