<?php
/**
 * PreSchedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Builder\V2;

// extends
use Cinemasunshine\Schedule\Builder\V2\PreSchedule as BaseBuilder;

use Cinemasunshine\Portal\Schedule\Entity\V2\Movie as MovieEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Schedule as ScheduleEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Schedules as SchedulesEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\Screen as ScreenEntity;
use Cinemasunshine\Portal\Schedule\Entity\V2\TIme as TimeEntity;

/**
 * Pre Schedule builder
 */
class PreSchedule extends BaseBuilder
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
        $movie->setIsPre(true);

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
