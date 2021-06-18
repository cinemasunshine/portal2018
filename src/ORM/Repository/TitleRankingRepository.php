<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Title;
use App\ORM\Entity\TitleRanking;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class TitleRankingRepository extends EntityRepository
{
    public function findOneById(int $id): ?TitleRanking
    {
        $alias = 'tr';

        $qb = $this->createQueryBuilder($alias);
        $qb
            ->addSelect('trr')
            ->join($alias . '.ranks', 'trr')
            ->addSelect('t')
            ->leftJoin('trr.title', 't')
            ->where('tr.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $query
            ->setFetchMode(Title::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getSingleResult();
    }
}
