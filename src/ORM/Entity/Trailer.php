<?php

/**
 * Trailer.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trailer entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\TrailerRepository")
 * @ORM\Table(name="trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Trailer extends AbstractEntity
{
    use SavedUserTrait;
    use SoftDeleteTrait;
    use TimestampableTrait;

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
     * title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $title;

    /**
     * name
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * youbute
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $youtube;

    /**
     * banner_image
     *
     * @var File
     * @ORM\OneToOne(targetEntity="File", fetch="EAGER")
     * @ORM\JoinColumn(name="banner_image_file_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $bannerImage;

    /**
     * banner_link_url
     *
     * @var string
     * @ORM\Column(type="string", name="banner_link_url")
     */
    protected $bannerLinkUrl;

    /**
     * page_trailers
     *
     * @var Collection<PageTrailer>
     * @ORM\OneToMany(targetEntity="PageTrailer", mappedBy="trailer", orphanRemoval=true)
     */
    protected $pageTrailers;

    /**
     * theater_trailers
     *
     * @var Collection<TheaterTrailer>
     * @ORM\OneToMany(targetEntity="TheaterTrailer", mappedBy="trailer", orphanRemoval=true)
     */
    protected $theaterTrailers;

    /**
     * special_site_trailers
     *
     * @var Collection<SpecialSiteTrailer>
     * @ORM\OneToMany(targetEntity="SpecialSiteTrailer", mappedBy="trailer", orphanRemoval=true)
     */
    protected $specialSiteTrailers;

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
     * get title
     *
     * @return Title|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * set title
     *
     * @param Title|null $title
     * @return void
     * @throws \LogicException
     */
    public function setTitle($title)
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
     * get youtube
     *
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * set youtube
     *
     * @param string $youtube
     * @return void
     * @throws \LogicException
     */
    public function setYoutube(string $youtube)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get banner_image
     *
     * @return File
     */
    public function getBannerImage()
    {
        return $this->bannerImage;
    }

    /**
     * set banner_image
     *
     * @param File $bannerImage
     * @return void
     * @throws \LogicException
     */
    public function setBannerImage(File $bannerImage)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get banner_link_url
     *
     * @return string
     */
    public function getBannerLinkUrl()
    {
        return $this->bannerLinkUrl;
    }

    /**
     * set banner_link_url
     *
     * @param string $bannerLinkUrl
     * @return void
     * @throws \LogicException
     */
    public function setBannerLinkUrl(string $bannerLinkUrl)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get page_trailers
     *
     * @return Collection
     */
    public function getPageTrailers()
    {
        return $this->pageTrailers;
    }

    /**
     * set page_trailers
     *
     * @param Collection $pageTrailers
     * @return void
     * @throws \LogicException
     */
    public function setPageTrailers(Collection $pageTrailers)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get theater_trailers
     *
     * @return Collection
     */
    public function getTheaterTrailers()
    {
        return $this->theaterTrailers;
    }

    /**
     * set theater_trailers
     *
     * @param Collection $theaterTrailers
     * @return void
     * @throws \LogicException
     */
    public function setTheaterTrailers(Collection $theaterTrailers)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get special_site_trailers
     *
     * @return Collection
     */
    public function getSpecialSiteTrailers()
    {
        return $this->specialSiteTrailers;
    }

    /**
     * set special_site_trailers
     *
     * @param Collection $specialSiteTrailers
     * @return void
     * @throws \LogicException
     */
    public function setSpecialSiteTrailers(Collection $specialSiteTrailers)
    {
        throw new \LogicException('Not allowed.');
    }
}
