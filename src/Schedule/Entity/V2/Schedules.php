<?php
/**
 * Schedules.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V2;

use Cinemasunshine\Schedule\Entity\V2\Schedules as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

use Cinemasunshine\Portal\Schedule\Collection\Schedule as ScheduleCollection;

/**
 * Schedules entity
 */
class Schedules extends BaseEntity implements PortalEntityInterface
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->schedule = new ScheduleCollection();
    }

    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array(
            'error'     => $this->getError(),
            'attention' => $this->getAttention(),
            'schedule'  => [],
        );

        foreach ($this->getSchedule() as $schedule) {
            $data['schedule'][] = $schedule->toArray();
        }

        return $data;
    }
}
