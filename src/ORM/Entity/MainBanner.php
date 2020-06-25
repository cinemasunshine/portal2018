<?php

/**
 * MainBanner.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\File as BaseFile;
use Cinemasunshine\ORM\Entity\MainBanner as BaseMainBanner;
use Doctrine\ORM\Mapping as ORM;

/**
 * MainBanner entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\MainBannerRepository")
 * @ORM\Table(name="main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class MainBanner extends BaseMainBanner
{
    /** @var array */
    protected static $linkTypes = [
        self::LINK_TYPE_NONE => 'リンクなし',
        self::LINK_TYPE_URL  => 'URL',
    ];

    /**
     * return link types
     *
     * @return array
     */
    public static function getLinkTypes()
    {
        return self::$linkTypes;
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
    public function setImage(BaseFile $image)
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
    public function setLinkType(int $linkType)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * is link_type none
     *
     * @return boolean
     */
    public function isLinkTypeNone()
    {
        return $this->getLinkType() === self::LINK_TYPE_NONE;
    }

    /**
     * is link_type URL
     *
     * @return boolean
     */
    public function isLinkTypeUrl()
    {
        return $this->getLinkType() === self::LINK_TYPE_URL;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setLinkUrl($linkUrl)
    {
        throw new \LogicException('Not allowed.');
    }
}
