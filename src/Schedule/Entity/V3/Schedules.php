<?php

declare(strict_types=1);

namespace App\Schedule\Entity\V3;

use App\Schedule\Collection\Schedule as ScheduleCollection;
use App\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Schedule\Entity\V3\Schedules as BaseEntity;

/**
 * Schedules entity
 */
class Schedules extends BaseEntity implements PortalEntityInterface
{
    public function __construct()
    {
        $this->scheduleCollection = new ScheduleCollection();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
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
