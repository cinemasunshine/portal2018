<?php
/**
 * Screen.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V1;

use Cinemasunshine\Schedule\Entity\V1\Screen as BaseEntity;
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
            'time' => array(),
        );

        foreach ($this->getTime() as $time) {
            $data['time'][] = $time->toArray();
        }

        return $data;
    }
}
