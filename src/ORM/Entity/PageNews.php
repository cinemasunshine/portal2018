<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\News as BaseNews;
use Cinemasunshine\ORM\Entities\Page as BasePage;
use Cinemasunshine\ORM\Entities\PageNews as BasePageNews;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * PageNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageNews extends BasePageNews
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
    public function setNews(BaseNews $news): void
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
