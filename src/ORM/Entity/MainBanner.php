<?php

/**
 * MainBanner.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MainBanner entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\MainBannerRepository")
 * @ORM\Table(name="main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class MainBanner extends AbstractEntity
{
    use SavedUserTrait;
    use SoftDeleteTrait;
    use TimestampableTrait;

    public const LINK_TYPE_NONE = 1;
    public const LINK_TYPE_URL = 2;

    /** @var array */
    protected static $linkTypes = [
        self::LINK_TYPE_NONE => 'リンクなし',
        self::LINK_TYPE_URL  => 'URL',
    ];

    /**
     * id
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * image
     *
     * @var File
     * @ORM\OneToOne(targetEntity="File", fetch="EAGER")
     * @ORM\JoinColumn(name="image_file_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $image;

    /**
     * name
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * link_type
     *
     * @var int
     * @ORM\Column(type="smallint", name="link_type", options={"unsigned"=true})
     */
    protected $linkType;

    /**
     * link_url
     *
     * @var string|null
     * @ORM\Column(type="string", name="link_url", nullable=true)
     */
    protected $linkUrl;

    /**
     * pages
     *
     * @var Collection<PageMainBanner>
     * @ORM\OneToMany(targetEntity="PageMainBanner", mappedBy="mainBanner")
     */
    protected $pages;

    /**
     * theaters
     *
     * @var Collection<TheaterMainBanner>
     * @ORM\OneToMany(targetEntity="TheaterMainBanner", mappedBy="mainBanner")
     */
    protected $theaters;

    /**
     * special_sites
     *
     * @var Collection<SpecialSiteMainBanner>
     * @ORM\OneToMany(targetEntity="SpecialSiteMainBanner", mappedBy="mainBanner")
     */
    protected $specialSites;

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
     * construct
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * get image
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * set image
     *
     * @param File $image
     * @return void
     * @throws \LogicException
     */
    public function setImage(File $image)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set name
     *
     * @param string $name
     * @return void
     * @throws \LogicException
     */
    public function setName(string $name)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get link_type
     *
     * @return int
     */
    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * set link_type
     *
     * @param int $linkType
     * @return void
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
     * get link_url
     *
     * @return string|null
     */
    public function getLinkUrl()
    {
        return $this->linkUrl;
    }

    /**
     * set link_url
     *
     * @param string|null $linkUrl
     * @return void
     * @throws \LogicException
     */
    public function setLinkUrl($linkUrl)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get pages
     *
     * @return Collection
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    /**
     * get theaters
     *
     * @return Collection
     */
    public function getTheaters(): Collection
    {
        return $this->theaters;
    }

    /**
     * get special_site
     *
     * @return Collection
     */
    public function getSpecialSite(): Collection
    {
        return $this->specialSites;
    }
}
