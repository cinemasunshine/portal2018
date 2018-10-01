<?php
/**
 * ScheduleRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\Schedule;

/**
 * Schedule repository class
 */
class ScheduleRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->where('s.isDeleted = false')
            ->andWhere('s.publicStartDt <= CURRENT_TIMESTAMP()')
            ->andWhere('s.publicEndDt > CURRENT_TIMESTAMP()');
        
        return $qb;
    }
    
    /**
     * find screeening
     *
     * @return Schedule[]
     */
    public function findScreening()
    {
        $qb = $this->getActiveQuery();
        
        $qb
            ->andWhere('s.startDate <= CURRENT_DATE()');
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find soon
     *
     * @return Schedule[]
     */
    public function findSoon()
    {
        $qb = $this->getActiveQuery();
        
        $qb
            ->andWhere('s.startDate > CURRENT_DATE()');
        
        return $qb->getQuery()->getResult();
    }
}
