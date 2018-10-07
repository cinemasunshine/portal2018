<?php
/**
 * Time.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V1;

use Cinemasunshine\Schedule\Entity\V1\Time as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

/**
 * Time
 */
class Time extends BaseEntity implements PortalEntityInterface
{
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
