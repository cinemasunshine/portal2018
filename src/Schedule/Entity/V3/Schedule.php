<?php

declare(strict_types=1);

namespace App\Schedule\Entity\V3;

use App\Schedule\Collection\Movie as MovieCollection;
use App\Schedule\Entity\PortalEntityInterface;
use Cinemasunshine\Schedule\Entity\V3\Schedule as BaseEntity;

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
        $this->movieCollection = new MovieCollection();
    }

    /**
     * to array
     *
     * @param bool $deep
     * @return array
     */
    public function toArray($deep = true)
    {
        $data = [
            'date'         => $this->getDate(),
            'usable'       => $this->getUsable(),
            'has_pre_sale' => $this->getHasPreSale(),
        ];

        if ($deep) {
            $data['movie'] = [];

            foreach ($this->getMovieCollection() as $movie) {
                $data['movie'][] = $movie->toArray();
            }
        }

        return $data;
    }
}
