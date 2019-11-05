<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Builder\V3;

// extends
use Cinemasunshine\Schedule\Builder\V3\Schedule as BaseBuilder;

use Cinemasunshine\Portal\Schedule\Entity\V3\Movie as MovieEntity;
use Cinemasunshine\Portal\Schedule\Entity\V3\Schedule as ScheduleEntity;
use Cinemasunshine\Portal\Schedule\Entity\V3\Schedules as SchedulesEntity;
use Cinemasunshine\Portal\Schedule\Entity\V3\Screen as ScreenEntity;
use Cinemasunshine\Portal\Schedule\Entity\V3\Time as TimeEntity;

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
