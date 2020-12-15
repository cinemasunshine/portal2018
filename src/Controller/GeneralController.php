<?php

namespace App\Controller;

use App\ORM\Entity;

abstract class GeneralController extends BaseController
{
    public const PAGE_ID = 1;

    /**
     * return page
     *
     * @param int $pageId
     * @return Entity\Page|null
     */
    protected function getPage(int $pageId)
    {
        return $this->em
            ->getRepository(Entity\Page::class)
            ->findOneById($pageId);
    }

    /**
     * return campaigns
     *
     * @param int $pageId
     * @return Entity\Campaign[]
     */
    protected function getCampaigns(int $pageId)
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findByPage($pageId);
    }

    /**
     * return theaters
     *
     * @return Entity\Theater[]
     */
    protected function getTheaters()
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findByActive();
    }
}
