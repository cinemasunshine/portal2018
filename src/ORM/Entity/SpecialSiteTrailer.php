<?php

/**
 * SpecialSiteTrailer.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\SpecialSite as BaseSpecialSite;
use Cinemasunshine\ORM\Entity\SpecialSiteTrailer as BaseSpecialSiteTrailer;
use Cinemasunshine\ORM\Entity\Trailer as BaseTrailer;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteTrailer extends BaseSpecialSiteTrailer
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
    public function setSpecialSite(BaseSpecialSite $specialSite)
    {
        throw new \LogicException('Not allowed.');
    }
}
