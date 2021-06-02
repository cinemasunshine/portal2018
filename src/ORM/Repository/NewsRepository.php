<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\News;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class NewsRepository extends EntityRepository
{
    protected function addActiveQuery(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere(sprintf('%s.isDeleted = false', $alias))
            ->andWhere($qb->expr()->andX(
                $qb->expr()->lte(sprintf('%s.startDt', $alias), 'CURRENT_TIMESTAMP()'),
                $qb->expr()->gt(sprintf('%s.endDt', $alias), 'CURRENT_TIMESTAMP()')
            ));
    }

    public function findOneById(int $id): ?News
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.id = :id', $alias))
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return News[]
     */
    public function findByPage(int $pageId, ?int $category = null, ?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $newsPagesAlias = 'np';
        $qb
            ->join(sprintf('%s.pages', $alias), $newsPagesAlias)
            ->andWhere(sprintf('%s.page = :page_id', $newsPagesAlias))
            ->setParameter('page_id', $pageId)
            ->orderBy(sprintf('%s.displayOrder', $newsPagesAlias), 'ASC');

        if ($category) {
            $qb
                ->andWhere(sprintf('%s.category = :category', $alias))
                ->setParameter('category', $category);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @param int[] $category
     * @return News[]
     */
    public function findByTheater(int $theaterId, array $category = [], ?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $newsTheatersAlias = 'nt';
        $qb
            ->join(sprintf('%s.theaters', $alias), $newsTheatersAlias)
            ->andWhere(sprintf('%s.theater = :theater_id', $newsTheatersAlias))
            ->setParameter('theater_id', $theaterId)
            ->orderBy(sprintf('%s.displayOrder', $newsTheatersAlias), 'ASC');

        if ($category) {
            $qb
                ->andWhere(sprintf('%s.category IN (:category)', $alias))
                ->setParameter('category', $category);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return News[]
     */
    public function findBySpecialSite(int $specialSiteId, ?int $category = null, ?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $newsSpecialSitesAlias = 'ns';
        $qb
            ->join(sprintf('%s.specialSites', $alias), $newsSpecialSitesAlias)
            ->andWhere(sprintf('%s.specialSite = :special_site_id', $newsSpecialSitesAlias))
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy(sprintf('%s.displayOrder', $newsSpecialSitesAlias), 'ASC');

        if ($category) {
            $qb
                ->andWhere(sprintf('%s.category IN (:category)', $alias))
                ->setParameter('category', $category);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return News[]
     */
    public function findByImax(?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.category = :category', $alias))
            ->setParameter('category', News::CATEGORY_IMAX)
            ->orderBy(sprintf('%s.createdAt', $alias), 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return News[]
     */
    public function findBy4dx(?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.category = :category', $alias))
            ->setParameter('category', News::CATEGORY_4DX)
            ->orderBy(sprintf('%s.createdAt', $alias), 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return News[]
     */
    public function findByScreenX(?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.category = :category', $alias))
            ->setParameter('category', News::CATEGORY_SCREENX)
            ->orderBy(sprintf('%s.createdAt', $alias), 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return News[]
     */
    public function findBy4DXScreen(?int $limit = null): array
    {
        $alias = 'n';
        $qb    = $this->createQueryBuilder($alias);

        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.category = :category', $alias))
            ->setParameter('category', News::CATEGORY_4DX_SCREEN)
            ->orderBy(sprintf('%s.createdAt', $alias), 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
