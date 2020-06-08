<?php

/**
 * Schedules.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Schedule\Entity\V3;

use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Portal\Schedule\Collection\Schedule as ScheduleCollection;
use Cinemasunshine\Schedule\Entity\V3\Schedules as BaseEntity;

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
        $this->scheduleCollection = new ScheduleCollection();
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

        foreach ($this->getScheduleCollection() as $schedule) {
            $data['schedule'][] = $schedule->toArray();
        }

        return $data;
    }
}
