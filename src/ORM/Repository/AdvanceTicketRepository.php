<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\AdvanceTicket;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Advance Ticket repository class
 */
class AdvanceTicketRepository extends EntityRepository
{
    protected function getActiveQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');

        /**
         * orderByでNULLを最後にするためのSELECT
         * ISNULL関数は使えないのでCASEで対応
         * 結果セットには不要なのでHIDDENを付ける
         */
        $addSelect = <<<SQL
CASE
    WHEN s.publishingExpectedDate IS NULL THEN 1
    ELSE 0
END AS HIDDEN publishingExpectedDateIsNull
SQL;

        $qb
            ->addSelect($addSelect)
            ->join('t.advanceSale', 's')
            ->where('t.isDeleted = false')
            ->andWhere('s.isDeleted = false')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->eq('t.isSalesEnd', 'false'),
                $qb->expr()->lte('t.publishingStartDt', 'CURRENT_TIMESTAMP()'),
                $qb->expr()->orX(
                    $qb->expr()->isNull('s.publishingExpectedDate'),
                    $qb->expr()->gt('s.publishingExpectedDate', 'CURRENT_DATE()')
                )
            ))
            /**
             * NULLを最後にする
             * IS NULLが使えないのでaddSelectでNULL並び替え用のカラムを追加
             */
            ->orderBy('publishingExpectedDateIsNull', 'ASC')
            ->addOrderBy('s.publishingExpectedDate', 'ASC');

        return $qb;
    }

    /**
     * @return AdvanceTicket[]
     */
    public function findByTheater(int $theaterId): array
    {
        $qb = $this->getActiveQuery();

        $qb
            ->andWhere('s.theater = :theater_id')
            ->setParameter('theater_id', $theaterId);

        return $qb->getQuery()->getResult();
    }
}
