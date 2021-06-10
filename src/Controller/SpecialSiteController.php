<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\Campaign;
use App\ORM\Entity\MainBanner;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Theater;
use App\ORM\Entity\Trailer;
use App\ORM\Repository\CampaignRepository;
use App\ORM\Repository\MainBannerRepository;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\ScheduleRepository;
use App\ORM\Repository\TheaterRepository;
use App\ORM\Repository\TrailerRepository;

abstract class SpecialSiteController extends BaseController
{
    protected function getCampaignRepository(): CampaignRepository
    {
        return $this->em->getRepository(Campaign::class);
    }

    protected function getNewsRepository(): NewsRepository
    {
        return $this->em->getRepository(News::class);
    }

    protected function getMainBannerRepository(): MainBannerRepository
    {
        return $this->em->getRepository(MainBanner::class);
    }

    protected function getScheduleRepository(): ScheduleRepository
    {
        return $this->em->getRepository(Schedule::class);
    }

    protected function getTheaterRepository(): TheaterRepository
    {
        return $this->em->getRepository(Theater::class);
    }

    protected function getTrailerRepository(): TrailerRepository
    {
        return $this->em->getRepository(Trailer::class);
    }
}
