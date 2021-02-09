<?php

declare(strict_types=1);

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
     * @param ScheduleInterface $schedule
     *
     * @throws InvalidArgumentException
     */
    public function add($schedule): void
    {
        if (! $schedule instanceof ScheduleInterface) {
            throw new InvalidArgumentException('should implement Entity\ScheduleInterface');
        }

        // 日付をキーとする
        $this->data[$schedule->getDate()] = $schedule;
    }

    public function has(string $date): bool
    {
        return isset($this->data[$date]);
    }

    public function get(string $date): ?ScheduleInterface
    {
        return $this->data[$date] ?? null;
    }

    /**
     * キーでソート（昇順）
     *
     * @link http://php.net/manual/ja/function.ksort.php
     */
    public function ksort(): bool
    {
        return ksort($this->data);
    }

    /**
     * キーでソート（降順）
     *
     * @link http://php.net/manual/ja/function.krsort.php
     */
    public function krsort(): bool
    {
        return krsort($this->data);
    }
}
