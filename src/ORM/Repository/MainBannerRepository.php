<?php
/**
 * MainBannerRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;

use Cinemasunshine\Portal\ORM\Entity\MainBanner;

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
            ->setParameter('page_id', $pageId);
        
        return $qb->getQuery()->getResult();
    }
}
