<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\File as BaseFile;
use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * Title entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Title extends BaseTitle
{
    /** @var array<int, string> */
    protected static array $ratingTypes = [
        '1' => 'G',
        '2' => 'PG12',
        '3' => 'R15+',
        '4' => 'R18+',
    ];

    /** @var array<int, string> */
    protected static array $universalTypes = [
        '1' => '音声上映',
        '2' => '字幕上映',
    ];

    /**
     * @return array<int, string>
     */
    public static function getRatingTypes(): array
    {
        return self::$ratingTypes;
    }

    /**
     * @return array<int, string>
     */
    public static function getUniversalTypes(): array
    {
        return self::$universalTypes;
    }

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
    public function setImage(?BaseFile $image): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setName(string $name): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setNameKana(?string $nameKana): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setNameOriginal(?string $nameOriginal): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setCredit(?string $credit): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setCatchcopy(?string $catchcopy): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setIntroduction(?string $introduction): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setDirector(?string $director): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setCast(?string $cast): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublishingExpectedDate($publishingExpectedDate): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setOfficialSite(?string $officialSite): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setRating(?int $rating): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * @return string[]
     */
    public function getUniversalLabel(): array
    {
        $univarsal = $this->getUniversal();
        $types     = self::getUniversalTypes();
        $labels    = [];

        foreach ($univarsal as $value) {
            if (isset($types[$value])) {
                $labels[] = $types[$value];
            }
        }

        return $labels;
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setUniversal(?array $universal): void
    {
        throw new LogicException('Not allowed.');
    }

    public function getTrailers(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => Criteria::DESC]);

        return $this->trailers->matching($criteria);
    }

    /**
     * {@inheritDoc}
     *
     * 表示順管理は想定していない。
     * 作品に紐付けられたものを登録された順でよいとのこと。
     */
    public function getCampaigns(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isDeleted', false))
            ->andWhere(Criteria::expr()->lte('startDt', new DateTime('now')))
            ->andWhere(Criteria::expr()->gt('endDt', new DateTime('now')))
            ->orderBy(['createdAt' => Criteria::ASC]);

        return $this->campaigns->matching($criteria);
    }
}
