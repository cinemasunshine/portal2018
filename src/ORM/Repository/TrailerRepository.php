<?php

/**
 * TrailerRepository.php
 */

namespace App\ORM\Repository;

use App\ORM\Entity\Trailer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Trailer repository class
 */
class TrailerRepository extends EntityRepository
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
     * find by page
     *
     * @param int $pageId
     * @return Trailer[]
     */
    public function findByPage(int $pageId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.pageTrailers', 'pt')
            ->andWhere('pt.page = :page_id')
            ->setParameter('page_id', $pageId);

        return $qb->getQuery()->getResult();
    }

    /**
     * find by theater
     *
     * @param int $theaterId
     * @return Trailer[]
     */
    public function findByTheater(int $theaterId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.theaterTrailers', 'tt')
            ->andWhere('tt.theater = :theater_id')
            ->setParameter('theater_id', $theaterId);

        return $qb->getQuery()->getResult();
    }

    /**
     * find by special_site
     *
     * @param int $specialSiteId
     * @return Trailer[]
     */
    public function findBySpecialSite(int $specialSiteId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.specialSiteTrailers', 'st')
            ->andWhere('st.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId);

        return $qb->getQuery()->getResult();
    }
}
