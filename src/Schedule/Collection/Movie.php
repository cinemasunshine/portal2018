<?php

namespace App\Schedule\Collection;

use Cinemasunshine\Schedule\Collection\Movie as Base;
use Cinemasunshine\Schedule\Entity\MovieInterface;
use InvalidArgumentException;

/**
 * Movie collection
 */
class Movie extends Base
{
    /**
     * add
     *
     * @param MovieInterface $movie
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function add($movie)
    {
        if (! $movie instanceof MovieInterface) {
            throw new InvalidArgumentException('should implement Entity\MovieInterface');
        }

        $this->data[$movie->getCode()] = $movie;
    }
}
