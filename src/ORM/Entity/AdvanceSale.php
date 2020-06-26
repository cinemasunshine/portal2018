<?php

/**
 * AdvanceSale.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\AdvanceSale as BaseAdvanceSale;
use Cinemasunshine\ORM\Entity\Theater as BaseTheater;
use Cinemasunshine\ORM\Entity\Title as BaseTitle;
use Doctrine\ORM\Mapping as ORM;

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
    public function setTheater(BaseTheater $theater)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setTitle(BaseTitle $title)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPublishingExpectedDate($publishingExpectedDate)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPublishingExpectedDateText(?string $publishingExpectedDateText)
    {
        throw new \LogicException('Not allowed.');
    }
}
