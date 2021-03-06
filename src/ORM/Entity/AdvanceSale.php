<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\AdvanceSale as BaseAdvanceSale;
use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * AdvanceSale entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="advance_sale", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdvanceSale extends BaseAdvanceSale
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
    public function setTheater(BaseTheater $theater): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setTitle(BaseTitle $title): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublishingExpectedDate($publishingExpectedDate): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublishingExpectedDateText(?string $publishingExpectedDateText): void
    {
        throw new LogicException('Not allowed.');
    }
}
