<?php

/**
 * ShowingFormat.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entities\Schedule as BaseSchedule;
use Cinemasunshine\ORM\Entities\ShowingFormat as BaseShowingFormat;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShowingFormat entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_format", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingFormat extends BaseShowingFormat
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
    public function setSchedule(BaseSchedule $schedule)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSystem(int $system)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSound(int $sound)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setVoice(int $voice)
    {
        throw new \LogicException('Not allowed.');
    }
}
