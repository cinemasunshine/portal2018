<?php

/**
 * Movie.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Schedule\Entity\V3;

use App\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Schedule\Entity\V3\Movie as BaseEntity;

/**
 * Movie entity
 */
class Movie extends BaseEntity implements PortalEntityInterface
{
    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'is_pre_sale'   => $this->getIsPreSale(),
            'code'          => $this->getCode(),
            'name'          => $this->getName(),
            'ename'         => $this->getEname(),
            'cname'         => $this->getCname(),
            'comment'       => $this->getComment(),
            'running_time'  => $this->getRunningTime(),
            'cm_time'       => $this->getCmTime(),
            'official_site' => $this->getOfficialSite(),
            'summary'       => $this->getSummary(),
            'short_code'    => $this->getShortCode(),
            'branch_code'   => $this->getBranchCode(),
            'screen'        => [],
        ];

        foreach ($this->getScreenCollection() as $screen) {
            $data['screen'][] = $screen->toArray();
        }

        return $data;
    }
}
