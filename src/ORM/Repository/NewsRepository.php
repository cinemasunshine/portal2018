<?php

/**
 * NewsRepository.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\ORM\Repository;

use App\ORM\Entity\News;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * News repository class
 */
class NewsRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->where('n.isDeleted = false')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->lte('n.startDt', 'CURRENT_TIMESTAMP()'),
                $qb->expr()->gt('n.endDt', 'CURRENT_TIMESTAMP()')
            ));

        return $qb;
    }

    /**
     * find one by id
     *
     * @param int $id
     * @return News|null
     */
    public function findOneById(int $id)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('n.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * find by page
     *
     * @param int $pageId
     * @param int $category
     * @param int $limit
     * @return News[]
     */
    public function findByPage(int $pageId, int $category = null, int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('n.pages', 'pn')
            ->andWhere('pn.page = :page_id')
            ->setParameter('page_id', $pageId)
            ->orderBy('pn.displayOrder', 'ASC');

        if ($category) {
            $qb
                ->andWhere('n.category = :category')
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
     * find by theater
     *
     * @param int      $theaterId
     * @param array    $category
     * @param int|null $limit
     * @return News[]
     */
    public function findByTheater(int $theaterId, array $category = [], ?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('n.theaters', 'pt')
            ->andWhere('pt.theater = :theater_id')
            ->setParameter('theater_id', $theaterId)
            ->orderBy('pt.displayOrder', 'ASC');

        if ($category) {
            $qb
                ->andWhere('n.category IN (:category)')
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
     * find by special_site
     *
     * @param int      $specialSiteId
     * @param int|null $category
     * @param int|null $limit
     * @return News[]
     */
    public function findBySpecialSite(int $specialSiteId, ?int $category = null, ?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->join('n.specialSites', 'sn')
            ->andWhere('sn.specialSite = :special_site_id')
            ->setParameter('special_site_id', $specialSiteId)
            ->orderBy('sn.displayOrder', 'ASC');

        if ($category) {
            $qb
                ->andWhere('n.category = :category')
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
     * find by IMAX
     *
     * @param int|null $limit
     * @return News[]
     */
    public function findByImax(?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('n.category = :category')
            ->setParameter('category', News::CATEGORY_IMAX)
            ->orderBy('n.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by 4DX
     *
     * @param int|null $limit
     * @return News[]
     */
    public function findBy4dx(?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('n.category = :category')
            ->setParameter('category', News::CATEGORY_4DX)
            ->orderBy('n.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by ScreenX
     *
     * @param int|null $limit
     * @return News[]
     */
    public function findByScreenX(?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('n.category = :category')
            ->setParameter('category', News::CATEGORY_SCREENX)
            ->orderBy('n.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * find by 4DX Screen
     *
     * @param int|null $limit
     * @return News[]
     */
    public function findBy4DXScreen(?int $limit = null)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('n.category = :category')
            ->setParameter('category', News::CATEGORY_4DX_SCREEN)
            ->orderBy('n.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(News::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
