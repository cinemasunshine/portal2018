<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule\Entity\V2;

use Cinemasunshine\Schedule\Entity\V2\Schedule as BaseEntity;
use Cinemasunshine\Portal\Schedule\Entity\PortalEntityInterface;

use Cinemasunshine\Portal\Schedule\Collection\Movie as MovieCollection;

/**
 * Schedule
 */
class Schedule extends BaseEntity implements PortalEntityInterface
{
    /** @var bool 先行販売含む */
    protected $hasPreSale;

    /**
     * get hasPreSale
     *
     * @return boolean
     */
    public function getHasPreSale()
    {
        return $this->hasPreSale;
    }

    /**
     * set hasPreSale
     *
     * @param boolean $hasPreSale
     */
    public function setHasPreSale(bool $hasPreSale)
    {
        $this->hasPreSale = $hasPreSale;
    }
    
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
            'date'         => $this->getDate(),
            'usable'       => $this->getUsable(),
            'has_pre_sale' => $this->getHasPreSale(),
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
