<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity;

abstract class GeneralController extends BaseController
{
    public const PAGE_ID = 1;

    protected function getPage(int $pageId): ?Entity\Page
    {
        return $this->em
            ->getRepository(Entity\Page::class)
            ->findOneById($pageId);
    }

    /**
     * @return Entity\Campaign[]
     */
    protected function getCampaigns(int $pageId): array
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findByPage($pageId);
    }

    /**
     * @return Entity\Theater[]
     */
    protected function getTheaters(): array
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findByActive();
    }
}
