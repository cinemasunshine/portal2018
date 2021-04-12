<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\MainBanner as BaseMainBanner;
use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Cinemasunshine\ORM\Entities\TheaterMainBanner as BaseTheaterMainBanner;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * TheaterMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterMainBanner extends BaseTheaterMainBanner
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
    public function setMainBanner(BaseMainBanner $mainBanner): void
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

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setDisplayOrder(int $displayOrder): void
    {
        throw new LogicException('Not allowed.');
    }
}
