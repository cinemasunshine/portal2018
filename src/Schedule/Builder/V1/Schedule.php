<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Builder\V1;

// extends
use Cinemasunshine\Schedule\Builder\V1\Schedule as BaseBuilder;

use Cinemasunshine\Portal\Schedule\Entity\V1\Movie as MovieEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Schedule as ScheduleEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Schedules as SchedulesEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\Screen as ScreenEntity;
use Cinemasunshine\Portal\Schedule\Entity\V1\TIme as TimeEntity;

/**
 * Schedule builder
 */
class Schedule extends BaseBuilder
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
     * build movie
     *
     * @param \SimpleXMLElement $xmlElement
     * @param \Cinemasunshine\Schedule\Entity\MovieInterface $movie
     * @return \Cinemasunshine\Schedule\Entity\MovieInterface
     */
    protected function buildMovie(
        \SimpleXMLElement $xmlElement, \Cinemasunshine\Schedule\Entity\MovieInterface $movie
    ) {
        $movie = parent::buildMovie($xmlElement, $movie);
        $movie->setIsPre(false);

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