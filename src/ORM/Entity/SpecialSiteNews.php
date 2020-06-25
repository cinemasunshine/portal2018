<?php

/**
 * SpecialSiteNews.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\News as BaseNews;
use Cinemasunshine\ORM\Entity\SpecialSite as BaseSpecialSite;
use Cinemasunshine\ORM\Entity\SpecialSiteNews as BaseSpecialSiteNews;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteNews extends BaseSpecialSiteNews
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
    public function setNews(BaseNews $news)
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
