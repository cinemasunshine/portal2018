<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\OyakoCinemaSchedule as BaseOyakoCinemaSchedule;
use Cinemasunshine\ORM\Entities\OyakoCinemaTheater as BaseOyakoCinemaTheater;
use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaTheater entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="oyako_cinema_theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaTheater extends BaseOyakoCinemaTheater
{
    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setOyakoCinemaSchedule(BaseOyakoCinemaSchedule $oyakoCinemaSchedule)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setTheater(BaseTheater $theater)
    {
        throw new \LogicException('Not allowed.');
    }
}
