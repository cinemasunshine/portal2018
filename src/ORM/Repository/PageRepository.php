<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Page;
use Cinemasunshine\ORM\Repositories\PageRepository as BaseRepository;

/**
 * @extends BaseRepository<Page>
 */
class PageRepository extends BaseRepository
{
    /**
     * @param int $id
     * @return Page|null
     */
    public function findOneById(int $id)
    {
        $alias = 'p';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere($alias . '.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
