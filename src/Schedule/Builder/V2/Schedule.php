<?php

/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Builder\V2;

// extends
use Cinemasunshine\Schedule\Builder\V2\Schedule as BaseBuilder;
use Cinemasunshine\Portal\Schedule\Entity\V2\Movie as MovieEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Schedule as ScheduleEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Schedules as SchedulesEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Screen as ScreenEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Time as TimeEntity;

/**
 * Schedule
 */
class Schedule extends BaseBuilder
{
    /** @var string */
    protected $purchaseBaseUrl;
    
    /**
     * construct
     *
     * @param string $purchaseBaseUrl
     */
    public function __construct(string $purchaseBaseUrl)
    {
        $this->purchaseBaseUrl = $purchaseBaseUrl;
    }
    
    /**
     * create Schedules entity
     *
     * @return SchedulesEntity
     */
    protected function createSchedulesEntity()
    {
        return new SchedulesEntity();
    }
    
    /**
     * @return ScheduleEntity
     */
    protected function createScheduleEntity()
    {
        $schedule = new ScheduleEntity();
        $schedule->setHasPreSale(false);
        
        return $schedule;
    }

    /**
     * @return MovieEntity
     */
    protected function createMovieEntity()
    {
        $movie = new MovieEntity();
        $movie->setIsPreSale(false);
        
        return $movie;
    }

    /**
     * @return ScreenEntity
     */
    protected function createScreenEntity()
    {
        return new ScreenEntity();
    }

    /**
     * @return TimeEntity
     */
    protected function createTimeEntity()
    {
        return new TimeEntity($this->purchaseBaseUrl);
    }
}
