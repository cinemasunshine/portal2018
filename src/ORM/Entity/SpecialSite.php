<?php

/**
 * SpecialSite.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSite entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSite extends AbstractEntity
{
    use SoftDeleteTrait;
    use TimestampableTrait;

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
     * theaters
     *
     * @var Collection<Theater>
     * @ORM\ManyToMany(targetEntity="Theater", mappedBy="specialSites")
     */
    protected $theaters;

    /**
     * campaigns
     *
     * @var Collection<SpecialSiteCampaign>
     * @ORM\OneToMany(targetEntity="SpecialSiteCampaign", mappedBy="specialSite", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $campaigns;

    /**
     * news_list
     *
     * @var Collection<SpecialSiteNews>
     * @ORM\OneToMany(targetEntity="SpecialSiteNews", mappedBy="specialSite", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $newsList;

    /**
     * main_banners
     *
     * @var Collection<SpecialSiteMainBanner>
     * @ORM\OneToMany(targetEntity="SpecialSiteMainBanner", mappedBy="specialSite", orphanRemoval=true)
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $mainBanners;


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
     * get theaters
     *
     * @return Collection
     */
    public function getTheaters()
    {
        return $this->theaters;
    }

    /**
     * get campaigns
     *
     * @return Collection
     */
    public function getCampaigns(): Collection
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
