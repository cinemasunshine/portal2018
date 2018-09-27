<?php
/**
 * NewsRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\News;

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
            ->where('n.isDeleted = false');
        
        return $qb;
    }
    
    /**
     * find by page
     *
     * @param int $pageId
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
        
        return $qb->getQuery()->getResult();
    }
}