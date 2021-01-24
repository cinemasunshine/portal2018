<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Campaign as BaseCampaign;
use Cinemasunshine\ORM\Entities\SpecialSite as BaseSpecialSite;
use Cinemasunshine\ORM\Entities\SpecialSiteCampaign as BaseSpecialSiteCampaign;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * SpecialSiteCampaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteCampaign extends BaseSpecialSiteCampaign
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
    public function setCampaign(BaseCampaign $campaign)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setSpecialSite(BaseSpecialSite $specialSite)
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
