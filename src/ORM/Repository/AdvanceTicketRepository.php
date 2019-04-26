<?php
/**
 * AdvanceTicketRepository.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\AdvanceTicket;

/**
 * Advance Ticket repository class
 */
class AdvanceTicketRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
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
     * find by theater
     *
     * @param int $theaterId
     * @return AdvanceTicket[]
     */
    public function findByTheater(int $theaterId)
    {
        $qb = $this->getActiveQuery();
        
        $qb
            ->andWhere('s.theater = :theater_id')
            ->setParameter('theater_id', $theaterId);
        
        return $qb->getQuery()->getResult();
    }
}
