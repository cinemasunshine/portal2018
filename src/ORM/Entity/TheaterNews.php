<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\News as BaseNews;
use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Cinemasunshine\ORM\Entities\TheaterNews as BaseTheaterNews;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * TheaterNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterNews extends BaseTheaterNews
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
    public function setTheater(BaseTheater $theater): void
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
