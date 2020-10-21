<?php

/**
 * Time.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Schedule\Entity\V3;

use App\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Schedule\Entity\V3\Time as BaseEntity;

/**
 * Time entity
 */
class Time extends BaseEntity implements PortalEntityInterface
{
    /** @var string */
    protected $baseUrl;

    /**
     * construct
     *
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * get url
     *
     * @return string
     * @link https://m-p.backlog.jp/view/SSKTS-635
     * @link https://m-p.backlog.jp/view/SSKTS-738
     */
    public function getUrl(): string
    {
        $timeBegin = str_replace(':', '', $this->getStart());

        $screen     = $this->getScreen();
        $screenCode = $screen->getCode();

        $movie          = $screen->getMovie();
        $titleCode      = $movie->getShortCode();
        $titleBranchNum = $movie->getBranchCode();

        $schedule  = $movie->getSchedule();
        $dateJouei = str_replace('-', '', $schedule->getDate());

        $theaterCode = $schedule->getSchedules()->getTheaterCode();

        // SSKTS-60
        $theaterCode = str_pad($theaterCode, 3, '0', STR_PAD_LEFT);

        $id = $theaterCode . $titleCode . $titleBranchNum . $dateJouei . $screenCode . $timeBegin;

        return $this->baseUrl . '/purchase/index.html?id=' . $id;
    }

    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'start'     => $this->getStart(),
            'end'       => $this->getEnd(),
            'available' => $this->getAvailable(),
            'url'       => $this->getUrl(),
            'late'      => $this->getLate(),
        ];
    }
}
