<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Campaign as BaseCampaign;
use Cinemasunshine\ORM\Entities\Page as BasePage;
use Cinemasunshine\ORM\Entities\PageCampaign as BasePageCampaign;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * PageCampaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageCampaign extends BasePageCampaign
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
    public function setCampaign(BaseCampaign $campaign): void
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
