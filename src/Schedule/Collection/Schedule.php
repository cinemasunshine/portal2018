<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Collection;

use Cinemasunshine\Schedule\Collection\Schedule as Base;
use Cinemasunshine\Schedule\Entity\ScheduleInterface;

/**
 * Schedule collection
 */
class Schedule extends Base
{
    /**
     * add
     *
     * @param ScheduleInterface $schedule
     * @throws \InvalidArgumentException
     */
    public function add($schedule)
    {
        if (!$schedule instanceof ScheduleInterface) {
            throw new \InvalidArgumentException('should implement Entity\ScheduleInterface');
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
        return isset($this->data[$date]) ? $this->data[$date] : null;
    }

    /**
     * キーでソート（昇順）
     * 
     * @return bool
     * @link http://php.net/manual/ja/function.ksort.php
     */
    public function ksort()
    {
        return ksort($this->data);
    }

    /**
     * キーでソート（降順）
     *
     * @return bool
     * @link http://php.net/manual/ja/function.krsort.php
     */
    public function krsort()
    {
        return krsort($this->data);
    }
}
