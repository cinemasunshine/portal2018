<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Theater as BaseTheater;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * Theater entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\TheaterRepository")
 * @ORM\Table(name="theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Theater extends BaseTheater
{
    /** @var array<int, string> */
    protected static $areas = [
        1 => '関東',
        2 => '北陸・中部',
        3 => '関西',
        4 => '中国・四国',
        5 => '九州',
    ];

    /**
     * @return array<int, string>
     */
    public static function getAreas(): array
    {
        return self::$areas;
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
    public function setName(string $name): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setNameJa(string $nameJa): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setArea(int $area): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setMasterVersion(int $masterVersion): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setMasterCode(?string $masterCode): void
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

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setStatus(int $status): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * is status closed
     */
    public function isStatusClosed(): bool
    {
        return $this->getStatus() === self::STATUS_CLOSED;
    }
}
