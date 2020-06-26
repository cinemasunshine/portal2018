<?php

/**
 * CampaignRepository.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Cinemasunshine\Portal\ORM\Entity\Campaign;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * Campaign repository class
 */
class CampaignRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c.isDeleted = false')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->lte('c.startDt', 'CURRENT_TIMESTAMP()'),
                $qb->expr()->gt('c.endDt', 'CURRENT_TIMESTAMP()')
            ));

        return $qb;
    }

    /**
     * find by page
     *
     * @param int $pageId
     * @return Campaign[]
     */
    public function findByPage(int $pageId)
    {
        $qb = $this->getActiveQuery();

        $qb
            ->join('c.pages', 'pc')
            ->andWhere('pc.page = :page_id')
            ->setParameter('page_id', $pageId)
            ->orderBy('pc.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by theater
     *
     * @param int $theaterId
     * @return Campaign[]
     */
    public function findByTheater(int $theaterId)
    {
        $qb = $this->getActiveQuery();

        $qb
            ->join('c.theaters', 'tc')
            ->andWhere('tc.theater = :theater_id')
            ->setParameter('theater_id', $theaterId)
            ->orderBy('tc.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by special_site
     *
     * @param int $specialSiteId
     * @return Campaign[]
     */
    public function findBySpecialSite(int $specialSiteId)
    {
        $qb = $this->getActiveQuery();

        $qb
            ->join('c.specialSites', 'sc')
            ->andWhere('sc.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy('sc.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
