<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\MainBanner as BaseMainBanner;
use Cinemasunshine\ORM\Entities\SpecialSite as BaseSpecialSite;
use Cinemasunshine\ORM\Entities\SpecialSiteMainBanner as BaseSpecialSiteMainBanner;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * SpecialSiteMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteMainBanner extends BaseSpecialSiteMainBanner
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
    public function setSpecialSite(BaseSpecialSite $specialSite): void
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
