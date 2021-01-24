<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Schedule as BaseSchedule;
use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * Schedule entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\ScheduleRepository")
 * @ORM\Table(name="schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Schedule extends BaseSchedule
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
    public function setTitle(BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setStartDate($startDate)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setEndDate($endDate)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublicStartDt($publicStartDt)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublicEndDt($publicEndDt)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRemark(?string $remark)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setShowingFormats(Collection $showingFormats)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setShowingTheaters(Collection $showingTheaters)
    {
        throw new LogicException('Not allowed.');
    }
}
