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
        $qb
            ->join('t.advanceSale', 's')
            ->where('t.isDeleted = false')
            ->andWhere('s.isDeleted = false')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->eq('t.isSalesEnd', 'false'),
                $qb->expr()->lte('t.releaseDt', 'CURRENT_TIMESTAMP()'),
                $qb->expr()->orX(
                    $qb->expr()->isNull('s.publishingExpectedDate'),
                    $qb->expr()->gte('s.publishingExpectedDate', 'CURRENT_DATE()')
                )
            ));
        
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