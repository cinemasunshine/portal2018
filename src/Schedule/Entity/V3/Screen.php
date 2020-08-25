<?php

/**
 * Screen.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Schedule\Entity\V3;

use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Schedule\Entity\V3\Screen as BaseEntity;

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
        $data = [
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'time' => [],
        ];

        foreach ($this->getTimeCollection() as $time) {
            $data['time'][] = $time->toArray();
        }

        return $data;
    }
}
