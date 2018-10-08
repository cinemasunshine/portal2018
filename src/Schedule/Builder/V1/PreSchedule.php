<?php
/**
 * PreSchedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Builder\V1;

// extends
use Cinemasunshine\Schedule\Builder\V1\PreSchedule as BaseBuilder;

use Cinemasunshine\Portal\Schedule\Entity\V1\Movie as MovieEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Schedule as ScheduleEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Schedules as SchedulesEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Screen as ScreenEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\TIme as TimeEntity;

/**
 * Pre Schedule builder
 */
class PreSchedule extends BaseBuilder
{
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
        $schedule->setHasPreSale(true);
        
        return $schedule;
    }

    /**
     * @return MovieEntity
     */
    protected function createMovieEntity()
    {
        $movie = new MovieEntity();
        $movie->setIsPreSale(true);
        
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
        return new TimeEntity();
    }
}
