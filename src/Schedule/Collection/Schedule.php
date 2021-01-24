<?php

namespace App\Schedule\Collection;

use Cinemasunshine\Schedule\Collection\Schedule as Base;
use Cinemasunshine\Schedule\Entity\ScheduleInterface;
use InvalidArgumentException;

/**
 * Schedule collection
 */
class Schedule extends Base
{
    /**
     * add
     *
     * @param ScheduleInterface $schedule
     *
     * @throws InvalidArgumentException
     */
    public function add($schedule)
    {
        if (! $schedule instanceof ScheduleInterface) {
            throw new InvalidArgumentException('should implement Entity\ScheduleInterface');
        }

        // 日付をキーとする
        $this->data[$schedule->getDate()] = $schedule;
    }

    /**
     * has
     *
     * @param string $date
     * @return bool
     */
    public function has($date)
    {
        return isset($this->data[$date]);
    }

    /**
     * get
     *
     * @param string $date
     * @return ScheduleInterface
     */
    public function get($date)
    {
        return $this->data[$date] ?? null;
    }

    /**
     * キーでソート（昇順）
     *
     * @link http://php.net/manual/ja/function.ksort.php
     *
     * @return bool
     */
    public function ksort()
    {
        return ksort($this->data);
    }

    /**
     * キーでソート（降順）
     *
     * @link http://php.net/manual/ja/function.krsort.php
     *
     * @return bool
     */
    public function krsort()
    {
        return krsort($this->data);
    }
}
