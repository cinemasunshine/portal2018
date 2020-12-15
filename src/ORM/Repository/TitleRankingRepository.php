<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Title;
use App\ORM\Entity\TitleRanking;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * TitleRanking repository class
 */
class TitleRankingRepository extends EntityRepository
{
    /**
     * find one by id
     *
     * @param integer $id
     * @return TitleRanking
     */
    public function findOneById(int $id): ?TitleRanking
    {
        $qb = $this->createQueryBuilder('tr');
        $qb
            ->addSelect('t1')
            ->leftJoin('tr.rank1Title', 't1')
            ->addSelect('t2')
            ->leftJoin('tr.rank2Title', 't2')
            ->addSelect('t3')
            ->leftJoin('tr.rank3Title', 't3')
            ->addSelect('t4')
            ->leftJoin('tr.rank4Title', 't4')
            ->addSelect('t5')
            ->leftJoin('tr.rank5Title', 't5')
            ->where('tr.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $query
            ->setFetchMode(Title::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getSingleResult();
    }
}
