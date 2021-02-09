<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Trailer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Trailer repository class
 */
class TrailerRepository extends EntityRepository
{
    protected function getActiveQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->where('t.isDeleted = false');

        return $qb;
    }

    /**
     * @return Trailer[]
     */
    public function findByPage(int $pageId): array
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.pageTrailers', 'pt')
            ->andWhere('pt.page = :page_id')
            ->setParameter('page_id', $pageId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Trailer[]
     */
    public function findByTheater(int $theaterId): array
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.theaterTrailers', 'tt')
            ->andWhere('tt.theater = :theater_id')
            ->setParameter('theater_id', $theaterId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Trailer[]
     */
    public function findBySpecialSite(int $specialSiteId): array
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('t.specialSiteTrailers', 'st')
            ->andWhere('st.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId);

        return $qb->getQuery()->getResult();
    }
}
