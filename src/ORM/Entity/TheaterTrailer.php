<?php

/**
 * TheaterTrailer.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Cinemasunshine\ORM\Entities\TheaterTrailer as BaseTheaterTrailer;
use Cinemasunshine\ORM\Entities\Trailer as BaseTrailer;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterTrailer extends BaseTheaterTrailer
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
    public function setTrailer(BaseTrailer $trailer)
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
