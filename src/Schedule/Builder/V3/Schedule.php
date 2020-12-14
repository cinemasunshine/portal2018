<?php

/**
 * Schedule.php
 */

namespace App\Schedule\Builder\V3;

use App\Schedule\Entity\V3\Movie as MovieEntity;
use App\Schedule\Entity\V3\Schedule as ScheduleEntity;
use App\Schedule\Entity\V3\Schedules as SchedulesEntity;
use App\Schedule\Entity\V3\Screen as ScreenEntity;
use App\Schedule\Entity\V3\Time as TimeEntity;
use Cinemasunshine\Schedule\Builder\V3\Schedule as BaseBuilder;

/**
 * Schedule builder
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
        return new ScheduleEntity();
    }

    /**
     * @return MovieEntity
     */
    protected function createMovieEntity()
    {
        return new MovieEntity();
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
