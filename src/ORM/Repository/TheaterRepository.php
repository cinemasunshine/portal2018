<?php
/**
 * TheaterRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\Theater;

/**
 * Theater repository class
 */
class TheaterRepository extends EntityRepository
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
            ->where('t.isDeleted = false');
        
        return $qb;
    }
    
    /**
     * find by active
     *
     * @return Theater[]
     */
    public function findByActive()
    {
        $qb = $this->getActiveQuery();
        $qb
            ->orderBy('t.displayOrder', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
}
