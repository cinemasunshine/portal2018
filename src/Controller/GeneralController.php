<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\Campaign;
use App\ORM\Entity\Theater;
use App\ORM\Repository\CampaignRepository;
use App\ORM\Repository\TheaterRepository;

abstract class GeneralController extends BaseController
{
    protected const PAGE_ID = 1;

    protected function getCampaignRepository(): CampaignRepository
    {
        return $this->em->getRepository(Campaign::class);
    }

    /**
     * @return Campaign[]
     */
    protected function findCampaigns(): array
    {
        return $this->getCampaignRepository()
            ->findByPage(self::PAGE_ID);
    }

    protected function getTheaterRepository(): TheaterRepository
    {
        return $this->em->getRepository(Theater::class);
    }

    /**
     * @return Theater[]
     */
    protected function findTheaters(): array
    {
        return $this->getTheaterRepository()
            ->findByActive();
    }
}
