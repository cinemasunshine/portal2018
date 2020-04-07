<?php
/**
 * Screen.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V2;

use Cinemasunshine\Schedule\Entity\V2\Screen as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

/**
 * Screen
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
