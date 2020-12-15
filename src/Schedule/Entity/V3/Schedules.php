<?php

declare(strict_types=1);

namespace App\Schedule\Entity\V3;

use App\Schedule\Entity\PortalEntityInterface;
use App\Schedule\Collection\Schedule as ScheduleCollection;
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
        $data = [
            'error'     => $this->getError(),
            'attention' => $this->getAttention(),
            'schedule'  => [],
        ];

        foreach ($this->getScheduleCollection() as $schedule) {
            $data['schedule'][] = $schedule->toArray();
        }

        return $data;
    }
}
