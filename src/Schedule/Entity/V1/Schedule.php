<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V1;

use Cinemasunshine\Schedule\Entity\V1\Schedule as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

use Cinemasunshine\Portal\Schedule\Collection\Movie as MovieCollection;

/**
 * Schedule entity
 */
class Schedule extends BaseEntity implements PortalEntityInterface
{
    /**
     * construct
     */
    public function __construct()
    {
        $this->movie = new MovieCollection();
    }

    /**
     * to array
     *
     * @param bool $deep
     * @return array
     */
    public function toArray($deep = true)
    {
        $data = array(
            'date'   => $this->getDate(),
            'usable' => $this->getUsable(),
        );

        if ($deep) {
            $data['movie'] = array();

            foreach ($this->getMovie() as $movie) {
                $data['movie'][] = $movie->toArray();
            }
        }

        return $data;
    }
}
