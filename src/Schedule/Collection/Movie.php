<?php

declare(strict_types=1);

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
     * @param MovieInterface $movie
     *
     * @throws InvalidArgumentException
     */
    public function add($movie): void
    {
        if (! $movie instanceof MovieInterface) {
            throw new InvalidArgumentException('should implement Entity\MovieInterface');
        }

        $this->data[$movie->getCode()] = $movie;
    }
}
