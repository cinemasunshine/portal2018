<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\OyakoCinemaTitle;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * OyakoCinemaTitle repository class
 */
class OyakoCinemaTitleRepository extends EntityRepository
{
    protected function getActiveQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('oct');
        $qb
            ->where('oct.isDeleted = false');

        return $qb;
    }

    /**
     * @return OyakoCinemaTitle[]
     */
    public function findByActive(): array
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
