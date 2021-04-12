<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\OyakoCinemaTitle as BaseOyakoCinemaTitle;
use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * OyakoCinemaTitle entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\OyakoCinemaTitleRepository")
 * @ORM\Table(name="oyako_cinema_title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaTitle extends BaseOyakoCinemaTitle
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
    public function setTitle(BaseTitle $title): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setTitleUrl(string $titleUrl): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @return Collection
     */
    public function getOyakoCinemaSchedules(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gte('date', new DateTime('now')))
            ->orderBy(['date' => Criteria::ASC]);

        return $this->oyakoCinemaSchedules->matching($criteria);
    }
}
