<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\OyakoCinemaSchedule as BaseOyakoCinemaSchedule;
use Cinemasunshine\ORM\Entities\OyakoCinemaTitle as BaseOyakoCinemaTitle;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * OyakoCinemaSchedule entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="oyako_cinema_schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaSchedule extends BaseOyakoCinemaSchedule
{
    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function __construct()
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setOyakoCinemaTitle(BaseOyakoCinemaTitle $oyakoCinemaTitle): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setDate($date): void
    {
        throw new LogicException('Not allowed.');
    }
}
