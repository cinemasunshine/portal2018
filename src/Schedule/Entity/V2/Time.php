<?php
/**
 * Time.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V2;

// extends
use Cinemasunshine\Schedule\Entity\V2\Time as BaseEntity;

// implements
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

/**
 * Time
 */
class Time extends BaseEntity implements PortalEntityInterface
{
    /**
     * get url
     *
     * @return string
     * @link https://m-p.backlog.jp/view/SSKTS-635
     * @link https://m-p.backlog.jp/view/SSKTS-738
     */
    public function getUrl()
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

        throw new \Exception('todo：ベースURLの設定');
        return TICKETING_ENTRANCE_URL . '/purchase/index.html?id=' . $id;
    }

    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'start'     => $this->getStart(),
            'end'       => $this->getEnd(),
            'available' => $this->getAvailable(),
            'url'       => $this->getUrl(),
            'late'      => $this->getLate(),
        );
    }
}
