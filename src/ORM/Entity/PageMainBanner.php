<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\MainBanner as BaseMainBanner;
use Cinemasunshine\ORM\Entities\Page as BasePage;
use Cinemasunshine\ORM\Entities\PageMainBanner as BasePageMainBanner;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * PageMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageMainBanner extends BasePageMainBanner
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
    public function setMainBanner(BaseMainBanner $mainBanner)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPage(BasePage $page)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setDisplayOrder(int $displayOrder)
    {
        throw new LogicException('Not allowed.');
    }
}
