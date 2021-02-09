<?php

declare(strict_types=1);

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

    public function __construct(string $purchaseBaseUrl)
    {
        $this->purchaseBaseUrl = $purchaseBaseUrl;
    }

    protected function createSchedulesEntity(): SchedulesEntity
    {
        return new SchedulesEntity();
    }

    protected function createScheduleEntity(): ScheduleEntity
    {
        return new ScheduleEntity();
    }

    protected function createMovieEntity(): MovieEntity
    {
        return new MovieEntity();
    }

    protected function createScreenEntity(): ScreenEntity
    {
        return new ScreenEntity();
    }

    protected function createTimeEntity(): TimeEntity
    {
        return new TimeEntity($this->purchaseBaseUrl);
    }
}
