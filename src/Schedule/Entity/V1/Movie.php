<?php
/**
 * Movie.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V1;

use Cinemasunshine\Schedule\Entity\V1\Movie as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

/**
 * Movie
 */
class Movie extends BaseEntity implements PortalEntityInterface
{
    /** @var bool 先行販売 */
    protected $isPreSale;

    /**
     * get isPreSale
     *
     * @return boolean
     */
    public function getIsPreSale()
    {
        return $this->isPreSale;
    }

    /**
     * set isPreSale
     *
     * @param boolean $isPreSale
     */
    public function setIsPreSale(bool $isPreSale)
    {
        $this->isPreSale = $isPreSale;
    }

    /**
     * to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array(
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
            'screen'        => array(),
        );

        foreach ($this->getScreen() as $screen) {
            $data['screen'][] = $screen->toArray();
        }

        return $data;
    }
}
