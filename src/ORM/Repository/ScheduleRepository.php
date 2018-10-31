<?php
/**
 * ScheduleRepository.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cinemasunshine\Portal\ORM\Entity\Schedule;
use Cinemasunshine\Portal\ORM\Entity\ShowingFormat;

/**
 * Schedule repository class
 */
class ScheduleRepository extends EntityRepository
{
    /**
     * return active query
     *
     * @return QueryBuilder
     */
    protected function getActiveQuery()
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->where('s.isDeleted = false')
            ->andWhere('s.publicStartDt <= CURRENT_TIMESTAMP()')
            ->andWhere('s.publicEndDt > CURRENT_TIMESTAMP()')
            ->orderBy('s.startDate', 'ASC');
        
        return $qb;
    }
    
    /**
     * return acreening query
     *
     * @return QueryBuilder
     */
    protected function getScreeningQuery()
    {
        $qb = $this->getActiveQuery();
        
        $qb->andWhere('s.startDate <= CURRENT_DATE()');
        
        return $qb;
    }
    
    /**
     * find screeening
     *
     * @return Schedule[]
     */
    public function findScreening()
    {
        $qb = $this->getScreeningQuery();
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find screening for IMAX
     *
     * @return Schedule[]
     */
    public function findScreeningForImax()
    {
        $systems = [
            ShowingFormat::SYSTEM_IMAX,
            ShowingFormat::SYSTEM_IMAX3D,
        ];
        
        $qb = $this->getScreeningQuery();
        $qb
            ->join('s.showingFormats', 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find screening for 4DX
     *
     * @return Schedule[]
     */
    public function findScreeningFor4dx()
    {
        $systems = [
            ShowingFormat::SYSTEM_4DX,
            ShowingFormat::SYSTEM_4DX3D,
        ];
        
        $qb = $this->getScreeningQuery();
        $qb
            ->join('s.showingFormats', 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * return soon query
     *
     * @return QueryBuilder
     */
    protected function getSoonQuery()
    {
        $qb = $this->getActiveQuery();
        
        $qb->andWhere('s.startDate > CURRENT_DATE()');
        
        return $qb;
    }
    
    /**
     * find soon
     *
     * @return Schedule[]
     */
    public function findSoon()
    {
        $qb = $this->getSoonQuery();
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find soon for IMAX
     *
     * @return Schedule[]
     */
    public function findSoonForImax()
    {
        $systems = [
            ShowingFormat::SYSTEM_IMAX,
            ShowingFormat::SYSTEM_IMAX3D,
        ];
        
        $qb = $this->getSoonQuery();
        $qb
            ->join('s.showingFormats', 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find soon for 4DX
     *
     * @return Schedule[]
     */
    public function findSoonFor4dx()
    {
        $systems = [
            ShowingFormat::SYSTEM_4DX,
            ShowingFormat::SYSTEM_4DX3D,
        ];
        
        $qb = $this->getSoonQuery();
        $qb
            ->join('s.showingFormats', 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * find one by id
     *
     * @param int $id
     * @return Schedule|null
     */
    public function findOneById(int $id)
    {
        $qb = $this->getActiveQuery();
        $qb
            ->andWhere('s.id = :id')
            ->setParameter('id', $id);
            
        return $qb->getQuery()->getOneOrNullResult();
    }
}
