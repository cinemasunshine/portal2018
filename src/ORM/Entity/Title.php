<?php

/**
 * Title.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Title extends BaseTitle
{
    /** @var array */
    protected static $ratingTypes = [
        '1' => 'G',
        '2' => 'PG12',
        '3' => 'R15+',
        '4' => 'R18+',
    ];

    /** @var array */
    protected static $universalTypes = [
        '1' => '音声上映',
        '2' => '字幕上映',
    ];

    /**
     * Return rating types
     *
     * @return array
     */
    public static function getRatingTypes()
    {
        return self::$ratingTypes;
    }

    /**
     * Return universal types
     *
     * @return array
     */
    public static function getUniversalTypes()
    {
        return self::$universalTypes;
    }

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
    public function setImage($image)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setName(string $name)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setNameKana(?string $nameKana)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setNameOriginal(?string $nameOriginal)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setCredit(?string $credit)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setCatchcopy(?string $catchcopy)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setIntroduction(?string $introduction)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setDirector(?string $director)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setCast(?string $cast)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPublishingExpectedDate($publishingExpectedDate)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setOfficialSite(?string $officialSite)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setRating(?int $rating)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get univarsal label
     *
     * @return array
     */
    public function getUniversalLabel()
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
     * @throws \LogicException
     */
    public function setUniversal(?array $universal)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     */
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
            ->andWhere(Criteria::expr()->lte('startDt', new \DateTime('now')))
            ->andWhere(Criteria::expr()->gt('endDt', new \DateTime('now')))
            ->orderBy(['createdAt' => Criteria::ASC]);

        return $this->campaigns->matching($criteria);
    }
}
