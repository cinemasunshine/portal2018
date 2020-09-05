<?php

/**
 * TheaterRepository.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\ORM\Repository;

use App\ORM\Entity\Theater;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
        
        $statusList = [
            Theater::STATUS_OPEN,
            Theater::STATUS_CLOSED,
        ];
        $qb
            ->andWhere('t.status IN (:status)')
            ->setParameter('status', $statusList);
        
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
    
    /**
     * find one by name
     *
     * @param string $name
     * @return Theater|null
     */
    public function findOneByName(string $name)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('t.name = :name')
            ->setParameter('name', $name);
            
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    /**
     * find by special_site
     *
     * @param int $specialSiteId
     * @return Theater[]
     */
    public function findBySpecialSite(int $specialSiteId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.specialSites', 's')
            ->andWhere('s.id = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy('t.displayOrder', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
}
