<?php

/**
 * SpecialSiteMainBanner.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\MainBanner as BaseMainBanner;
use Cinemasunshine\ORM\Entity\SpecialSite as BaseSpecialSite;
use Cinemasunshine\ORM\Entity\SpecialSiteMainBanner as BaseSpecialSiteMainBanner;
use Doctrine\ORM\Mapping as ORM;

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
    public function setMainBanner(BaseMainBanner $mainBanner)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSpecialSite(BaseSpecialSite $specialSite)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setDisplayOrder(int $displayOrder)
    {
        throw new \LogicException('Not allowed.');
    }
}
