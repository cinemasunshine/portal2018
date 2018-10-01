<?php
/**
 * TitleRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\Title;

/**
 * Title repository class
 */
class TitleRepository extends EntityRepository
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
            ->join('t.schedules', 's')
            ->where('t.isDeleted = false')
            ->andWhere('s.publicStartDt <= CURRENT_TIMESTAMP()')
            ->andWhere('s.publicEndDt > CURRENT_TIMESTAMP()');
        
        return $qb;
    }
    
    /**
     * find one by id
     *
     * @param int $id
     * @return Title|null
     */
    public function findOneById(int $id)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('t.id = :id')
            ->setParameter('id', $id);
            
        return $qb->getQuery()->getOneOrNullResult();
    }
}
