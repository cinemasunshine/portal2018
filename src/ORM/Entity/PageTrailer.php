<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Page as BasePage;
use Cinemasunshine\ORM\Entities\PageTrailer as BasePageTrailer;
use Cinemasunshine\ORM\Entities\Trailer as BaseTrailer;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * PageTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageTrailer extends BasePageTrailer
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
    public function setTrailer(BaseTrailer $trailer): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPage(BasePage $page): void
    {
        throw new LogicException('Not allowed.');
    }
}
