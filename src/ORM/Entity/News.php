<?php
/**
 * News.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * News entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\NewsRepository")
 * @ORM\Table(name="news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class News extends AbstractEntity
{
    use SavedUserTrait;
    use SoftDeleteTrait;
    use TimestampableTrait;

    const CATEGORY_NEWS       = 1;
    const CATEGORY_INFO       = 2;
    const CATEGORY_IMAX       = 3;
    const CATEGORY_4DX        = 4;
    const CATEGORY_EVENT      = 5;
    const CATEGORY_SCREENX    = 6; // SASAKI-351
    const CATEGORY_4DX_SCREEN = 7; // SASAKI-432

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
     * image
     *
     * @var File|null
     * @ORM\OneToOne(targetEntity="File", fetch="EAGER")
     * @ORM\JoinColumn(name="image_file_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $image;

    /**
     * category
     *
     * @var int
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    protected $category;

    /**
     * headline
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $headline;

    /**
     * body
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * start_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="start_dt")
     */
    protected $startDt;

    /**
     * end_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="end_dt")
     */
    protected $endDt;

    /**
     * pages
     *
     * @var Collection<PageNews>
     * @ORM\OneToMany(targetEntity="PageNews", mappedBy="news")
     */
    protected $pages;

    /**
     * theaters
     *
     * @var Collection<TheaterNews>
     * @ORM\OneToMany(targetEntity="TheaterNews", mappedBy="news")
     */
    protected $theaters;

    /**
     * special_sites
     *
     * @var Collection<SpecialSiteNews>
     * @ORM\OneToMany(targetEntity="SpecialSiteNews", mappedBy="news")
     */
    protected $specialSites;


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
     * get image
     *
     * @return File|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * set image
     *
     * @param File|null $image
     * @return void
     * @throws \LogicException
     */
    public function setImage(?File $image)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * set category
     *
     * @param int $category
     * @return void
     * @throws \LogicException
     */
    public function setCategory(int $category)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get headline
     *
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * set headline
     *
     * @param string $headline
     * @return void
     * @throws \LogicException
     */
    public function setHeadline(string $headline)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * set body
     *
     * @param string $body
     * @return void
     * @throws \LogicException
     */
    public function setBody(string $body)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get start_dt
     *
     * @return \DateTime
     */
    public function getStartDt()
    {
        return $this->startDt;
    }

    /**
     * set start_dt
     *
     * @param \DateTime|string $startDt
     * @return void
     * @throws \LogicException
     */
    public function setStartDt($startDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get end_dt
     *
     * @return \DateTime
     */
    public function getEndDt()
    {
        return $this->endDt;
    }

    /**
     * set end_dt
     *
     * @param \DateTime|string $endDt
     * @return void
     * @throws \LogicException
     */
    public function setEndDt($endDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get pages
     *
     * @return Collection
     */
    public function getPages() : Collection
    {
        return $this->pages;
    }

    /**
     * get theaters
     *
     * @return Collection
     */
    public function getTheaters() : Collection
    {
        return $this->theaters;
    }

    /**
     * get special_sites
     *
     * @return Collection
     */
    public function getSpecialSites(): Collection
    {
        return $this->specialSites;
    }
}
