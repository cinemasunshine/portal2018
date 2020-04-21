<?php

/**
 * GeneralController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * General controller
 */
abstract class GeneralController extends BaseController
{
    const PAGE_ID = 1;
    
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
