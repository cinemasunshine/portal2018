<?php

/**
 * Screen.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V3;

use Cinemasunshine\Schedule\Entity\V3\Screen as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

/**
 * Screen entity
 */
class Screen extends BaseEntity implements PortalEntityInterface
{
    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array(
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'time' => array(),
        );

        foreach ($this->getTime() as $time) {
            $data['time'][] = $time->toArray();
        }

        return $data;
    }
}
