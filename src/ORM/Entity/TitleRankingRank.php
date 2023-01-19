<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Cinemasunshine\ORM\Entities\TitleRanking as BaseTitleRanking;
use Cinemasunshine\ORM\Entities\TitleRankingRank as BaseTitleRankingRank;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * TitleRankingRank entity class
 *
 * @ORM\Entity
 * @ORM\Table(name="title_ranking_rank", options={"collate"="utf8mb4_general_ci"})
 *
 * @method Title|null getTitle()
 */
class TitleRankingRank extends BaseTitleRankingRank
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
    public function setRanking(BaseTitleRanking $ranking): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setTitle(?BaseTitle $title): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank(int $rank): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setDetailUrl(?string $detailUrl): void
    {
        throw new LogicException('Not allowed.');
    }
}
