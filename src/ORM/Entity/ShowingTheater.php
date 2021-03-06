<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Schedule as BaseSchedule;
use Cinemasunshine\ORM\Entities\ShowingTheater as BaseShowingTheater;
use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * ShowingTheater entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingTheater extends BaseShowingTheater
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
    public function setSchedule(BaseSchedule $schedule): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setTheater(BaseTheater $theater): void
    {
        throw new LogicException('Not allowed.');
    }
}
