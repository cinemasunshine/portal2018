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
    public function __construct()
    {
        $this->movieCollection = new MovieCollection();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(bool $deep = true): array
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
