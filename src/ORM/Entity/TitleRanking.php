<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\TitleRanking as BaseTitleRanking;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * TitleRanking entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\TitleRankingRepository")
 * @ORM\Table(name="title_ranking", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 *
 * @method Collection<int,TitleRankingRank> getRanks()
 */
class TitleRanking extends BaseTitleRanking
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
    public function setFromDate($fromDate): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setToDate($toDate): void
    {
        throw new LogicException('Not allowed.');
    }
}
