<?php

/**
 * MainBannerRepository.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\ORM\Repository;

use App\ORM\Entity\MainBanner;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * MainBanner repository class
 */
class MainBannerRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('mb');
        $qb
            ->where('mb.isDeleted = false');

        return $qb;
    }

    /**
     * find by page_id
     *
     * @param int $pageId
     * @return MainBanner[]
     */
    public function findByPageId(int $pageId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('mb.pages', 'pmb')
            ->andWhere('pmb.page = :page_id')
            ->setParameter('page_id', $pageId)
            ->orderBy('pmb.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(MainBanner::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by theater id
     *
     * @param int $theaterId
     * @return MainBanner[]
     */
    public function findByTheaterId(int $theaterId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('mb.theaters', 'tmb')
            ->andWhere('tmb.theater = :theater_id')
            ->setParameter('theater_id', $theaterId)
            ->orderBy('tmb.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(MainBanner::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by special_site
     *
     * @param int $specialSiteId
     * @return MainBanner[]
     */
    public function findBySpecialSiteId(int $specialSiteId)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('mb.specialSites', 'smb')
            ->andWhere('smb.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy('smb.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(MainBanner::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
