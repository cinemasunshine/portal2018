<?php

/**
 * OyakoCinemaSchedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\OyakoCinemaSchedule as BaseOyakoCinemaSchedule;
use Cinemasunshine\ORM\Entity\OyakoCinemaTitle as BaseOyakoCinemaTitle;
use Doctrine\ORM\Mapping as ORM;

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
    public function setOyakoCinemaTitle(BaseOyakoCinemaTitle $oyakoCinemaTitle)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setDate($date)
    {
        throw new \LogicException('Not allowed.');
    }
}
