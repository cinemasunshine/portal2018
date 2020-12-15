<?php

namespace App\ORM\Repository;

use App\ORM\Entity\Theater;
use Cinemasunshine\ORM\Repositories\TheaterRepository as BaseRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends BaseRepository<Theater>
 */
class TheaterRepository extends BaseRepository
{
    /**
     * @param QueryBuilder $qb
     * @param string       $alias
     * @return void
     */
    protected function addActiveQuery(QueryBuilder $qb, string $alias)
    {
        parent::addActiveQuery($qb, $alias);

        $statuses = [
            Theater::STATUS_OPEN,
            Theater::STATUS_CLOSED,
        ];
        $qb
            ->andWhere($alias . '.status IN (:status)')
            ->setParameter('status', $statuses);
    }

    /**
     * @return Theater[]
     */
    public function findByActive()
    {
        $alias = 't';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb->orderBy($alias . '.displayOrder', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $name
     * @return Theater|null
     */
    public function findOneByName(string $name)
    {
        $alias = 't';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere($alias . '.name = :name')
            ->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $specialSiteId
     * @return Theater[]
     */
    public function findBySpecialSite(int $specialSiteId)
    {
        $alias = 't';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $ailiasSpecialSites = 's';
        $qb
            ->join($alias . '.specialSites', $ailiasSpecialSites)
            ->andWhere($ailiasSpecialSites . '.id = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy($alias . '.displayOrder', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
