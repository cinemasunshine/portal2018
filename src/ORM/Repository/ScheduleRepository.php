<?php

declare(strict_types=1);

namespace App\ORM\Repository;

use App\ORM\Entity\Schedule;
use App\ORM\Entity\ShowingFormat;
use App\ORM\Entity\Title;
use Cinemasunshine\ORM\Repositories\ScheduleRepository as BaseRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @extends BaseRepository<Schedule>
 */
class ScheduleRepository extends BaseRepository
{
    /**
     * @return Schedule[]
     */
    public function findNowShowing(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $aliasShowingFormats  = 'sf';
        $aliasShowingTheaters = 'st';
        $aliasTitle           = 't';
        $qb
            ->select(sprintf('%s, %s, %s, %s', $alias, $aliasShowingFormats, $aliasShowingTheaters, $aliasTitle))
            ->innerJoin($alias . '.showingFormats', $aliasShowingFormats)
            ->innerJoin($alias . '.showingTheaters', $aliasShowingTheaters)
            ->innerJoin($alias . '.title', $aliasTitle);

        $this->addNowShowingQuery($qb, $alias);

        $query = $qb->getQuery();
        $query
            ->setFetchMode(Title::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findNowShowingByTheaterId(int $theaterId): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        $aliasShowingTheaters = 'st';
        $qb
            ->join(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters)
            ->andWhere(sprintf('%s.theater = :theater', $aliasShowingTheaters))
            ->setParameter('theater', $theaterId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findNowShowingForImax(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        $systems = [
            ShowingFormat::SYSTEM_IMAX,
            ShowingFormat::SYSTEM_IMAX3D,
        ];

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findNowShowingFor4dx(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        $systems = [
            ShowingFormat::SYSTEM_4DX,
            ShowingFormat::SYSTEM_4DX3D,
        ];

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findNowShowingForScreenX(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system = :system')
            ->setParameter('system', ShowingFormat::SYSTEM_SCREENX);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findNowShowingFor4dxScreen(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system = :system')
            ->setParameter('system', ShowingFormat::SYSTEM_4DX_SCREEN);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoon(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $aliasShowingFormats  = 'sf';
        $aliasShowingTheaters = 'st';
        $aliasTitle           = 't';
        $qb
            ->select(sprintf('%s, %s, %s, %s', $alias, $aliasShowingFormats, $aliasShowingTheaters, $aliasTitle))
            ->innerJoin($alias . '.showingFormats', $aliasShowingFormats)
            ->innerJoin($alias . '.showingTheaters', $aliasShowingTheaters)
            ->innerJoin($alias . '.title', $aliasTitle);

        $this->addComingSoonQuery($qb, $alias);

        $query = $qb->getQuery();
        $query
            ->setFetchMode(Title::class, 'image', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoonByTheaterId(int $theaterId): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        $aliasShowingTheaters = 'st';
        $qb
            ->join(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters)
            ->andWhere(sprintf('%s.theater = :theater', $aliasShowingTheaters))
            ->setParameter('theater', $theaterId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoonForImax(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        $systems = [
            ShowingFormat::SYSTEM_IMAX,
            ShowingFormat::SYSTEM_IMAX3D,
        ];

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoonFor4dx(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        $systems = [
            ShowingFormat::SYSTEM_4DX,
            ShowingFormat::SYSTEM_4DX3D,
        ];

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system IN (:systems)')
            ->setParameter('systems', $systems);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoonForScreenX(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system = :system')
            ->setParameter('system', ShowingFormat::SYSTEM_SCREENX);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Schedule[]
     */
    public function findCommingSoonFor4dxScreen(): array
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        $qb
            ->join(sprintf('%s.showingFormats', $alias), 'sf')
            ->andWhere('sf.system = :system')
            ->setParameter('system', ShowingFormat::SYSTEM_4DX_SCREEN);

        return $qb->getQuery()->getResult();
    }

    public function findOneById(int $id): ?Schedule
    {
        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $this->addPublicQuery($qb, $alias);

        $qb
            ->andWhere('s.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
