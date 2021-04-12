<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Campaign;
use Cinemasunshine\ORM\Repositories\CampaignRepository as BaseRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseRepository<Campaign>
 */
class CampaignRepository extends BaseRepository
{
    protected function addActiveQuery(QueryBuilder $qb, string $alias): void
    {
        parent::addActiveQuery($qb, $alias);

        $qb->andWhere($qb->expr()->andX(
            $qb->expr()->lte($alias . '.startDt', 'CURRENT_TIMESTAMP()'),
            $qb->expr()->gt($alias . '.endDt', 'CURRENT_TIMESTAMP()')
        ));
    }

    /**
     * @return Campaign[]
     */
    public function findByPage(int $pageId): array
    {
        $alias = 'c';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $campaignPagesAlias = 'cp';
        $qb
            ->join($alias . '.pages', $campaignPagesAlias)
            ->andWhere($campaignPagesAlias . '.page = :page_id')
            ->setParameter('page_id', $pageId)
            ->orderBy($campaignPagesAlias . '.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return Campaign[]
     */
    public function findByTheater(int $theaterId): array
    {
        $alias = 'c';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $campaingTheatersAlias = 'ct';
        $qb
            ->join($alias . '.theaters', $campaingTheatersAlias)
            ->andWhere($campaingTheatersAlias . '.theater = :theater_id')
            ->setParameter('theater_id', $theaterId)
            ->orderBy($campaingTheatersAlias . '.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return Campaign[]
     */
    public function findBySpecialSite(int $specialSiteId): array
    {
        $alias = 'c';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $campaingSpecialSitesAlias = 'cs';
        $qb
            ->join($alias . '.specialSites', $campaingSpecialSitesAlias)
            ->andWhere($campaingSpecialSitesAlias . '.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy($campaingSpecialSitesAlias . '.displayOrder', 'ASC');

        $query = $qb->getQuery();
        $query->setFetchMode(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
