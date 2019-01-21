<?php
/**
 * Theater.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Theater entity class
 * 
 * @ORM\Entity(repositoryClass="Cinemasunshine\Portal\ORM\Repository\TheaterRepository")
 * @ORM\Table(name="theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Theater extends AbstractEntity
{
    use SoftDeleteTrait;
    use TimestampableTrait;
    
    const MASTER_VERSION_V1 = 1;
    const MASTER_VERSION_V2 = 2;
    
    const STATUS_PRE_OPEN = 1;
    const STATUS_OPEN     = 2;
    const STATUS_CLOSED   = 3;
    
    /** @var array */
    protected static $areas = [
        1 => '関東',
        2 => '北陸・中部',
        3 => '関西',
        4 => '中国・四国',
        5 => '九州',
    ];
    
    /**
     * id
     * 
     * @var int
     * @ORM\Id
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;
    
    /**
     * name
     * 
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;
    
    /** 
     * name_ja
     * 
     * @var string
     * @ORM\Column(type="string", name="name_ja")
     */
    protected $nameJa;
    
    /**
     * area
     * 
     * @var int
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    protected $area;
    
    /**
     * master_version
     *
     * @var int
     * @ORM\Column(type="smallint", name="master_version", options={"unsigned"=true})
     */
    protected $masterVersion;

    /**
     * master_code
     *
     * @var string
     * @ORM\Column(type="string", name="master_code", length=3, nullable=true, options={"fixed":true})
     */
    protected $masterCode;
    
    /**
     * display_order
     *
     * @var int
     * @ORM\Column(type="smallint", name="display_order", options={"unsigned"=true})
     */
    protected $displayOrder;
    
    /**
     * status
     *
     * @var int
     * @ORM\Column(type="smallint", name="status", options={"unsigned"=true})
     */
    protected $status;
    
    /**
     * meta
     *
     * @var TheaterMeta
     * @ORM\OneToOne(targetEntity="TheaterMeta", mappedBy="theater")
     */
    protected $meta;
    
    /**
     * special_sites
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="SpecialSite", inversedBy="theaters")
     * @ORM\JoinTable(name="theater_special_site",
     *      joinColumns={@ORM\JoinColumn(name="theater_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="special_site_id", referencedColumnName="id")}
     * )
     */
    protected $specialSites;
    
    /**
     * admin_users
     * 
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AdminUser", mappedBy="theater")
     */
    protected $adminUsers;
    
    /**
     * campaigns
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="TheaterCampaign", mappedBy="theater", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $campaigns;
    
    /**
     * news_list
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="TheaterNews", mappedBy="theater", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $newsList;
    
    /**
     * main_banners
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="TheaterMainBanner", mappedBy="theater", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $mainBanners;
    
    /**
     * return areas
     *
     * @return array
     */
    public static function getAreas()
    {
        return self::$areas;
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
     * get name_ja
     *
     * @return string
     */
    public function getNameJa()
    {
        return $this->nameJa;
    }
    
    /**
     * set name_ja
     *
     * @param string $nameJa
     * @return void
     * @throws \LogicException
     */
    public function setNameJa(string $nameJa)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get area
     *
     * @return int
     */
    public function getArea()
    {
        return $this->area;
    }
    
    /**
     * set area
     *
     * @param int $area
     * @return void
     * @throws \LogicException
     */
    public function setArea($area)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get master_version
     *
     * @return int
     */
    public function getMasterVersion()
    {
        return $this->masterVersion;
    }
    
    /**
     * set master_version
     *
     * @param int $masterVersion
     * @return void
     * @throws \LogicException
     */
    public function setMasterVersion($masterVersion)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get master_code
     *
     * @return string
     */
    public function getMasterCode()
    {
        return $this->masterCode;
    }
    
    /**
     * set master_code
     *
     * @param string $masterCode
     * @return void
     * @throws \LogicException
     */
    public function setMasterCode($masterCode)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get display_order
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
    
    /**
     * set display_order
     *
     * @param int $displayOrder
     * @return void
     * @throws \LogicException
     */
    public function setDisplayOrder(int $displayOrder)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * set status
     *
     * @param int $status
     * @return void
     * @throws \LogicException
     */
    public function setStatus(int $status)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get meta
     *
     * @return TheaterMeta
     */
    public function getMeta()
    {
        return $this->meta;
    }
    
    /**
     * get special_sites
     *
     * @return Collection
     */
    public function getSpecialSites()
    {
        return $this->specialSites;
    }
    
    /**
     * get admin_users
     *
     * @return ArrayCollection
     */
    public function getAdminUsers()
    {
        return $this->adminUsers;
    }
    
    /**
     * get campaigns
     *
     * @return Collection
     */
    public function getCampaigns() : Collection
    {
        return $this->campaigns;
    }
    
    /**
     * get news_list
     *
     * @return Collection
     */
    public function getNewsList(): Collection
    {
        return $this->newsList;
    }
    
    /**
     * get main_banners
     *
     * @return Collection
     */
    public function getMainBanners(): Collection
    {
        return $this->mainBanners;
    }
}