<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Cinemasunshine\ORM\Entities\TitleRanking as BaseTitleRanking;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * TitleRanking entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\TitleRankingRepository")
 * @ORM\Table(name="title_ranking", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
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
    public function setFromDate($fromDate)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setToDate($toDate)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank1Title(?BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank2Title(?BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank3Title(?BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank4Title(?BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRank5Title(?BaseTitle $title)
    {
        throw new LogicException('Not allowed.');
    }
}
