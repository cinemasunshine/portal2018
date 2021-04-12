<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\File as BaseFile;
use Cinemasunshine\ORM\Entities\MainBanner as BaseMainBanner;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * MainBanner entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\MainBannerRepository")
 * @ORM\Table(name="main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class MainBanner extends BaseMainBanner
{
    /** @var array<int, string> */
    protected static $linkTypes = [
        self::LINK_TYPE_NONE => 'リンクなし',
        self::LINK_TYPE_URL  => 'URL',
    ];

    /**
     * @return array<int, string>
     */
    public static function getLinkTypes(): array
    {
        return self::$linkTypes;
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
    public function setImage(BaseFile $image): void
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
    public function setLinkType(int $linkType): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * is link_type none
     */
    public function isLinkTypeNone(): bool
    {
        return $this->getLinkType() === self::LINK_TYPE_NONE;
    }

    /**
     * is link_type URL
     */
    public function isLinkTypeUrl(): bool
    {
        return $this->getLinkType() === self::LINK_TYPE_URL;
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setLinkUrl(?string $linkUrl): void
    {
        throw new LogicException('Not allowed.');
    }
}
