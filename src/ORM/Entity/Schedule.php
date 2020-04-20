<?php
/**
 * Schedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\ScheduleRepository")
 * @ORM\Table(name="schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Schedule extends AbstractEntity
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
     * @var Title
     * @ORM\ManyToOne(targetEntity="Title", fetch="EAGER")
     * @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $title;

    /**
     * start_date
     *
     * @var \DateTime
     * @ORM\Column(type="date", name="start_date")
     */
    protected $startDate;

    /**
     * end_date
     *
     * @var \DateTime
     * @ORM\Column(type="date", name="end_date")
     */
    protected $endDate;

    /**
     * public_start_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="public_start_dt")
     */
    protected $publicStartDt;

    /**
     * public_end_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="public_end_dt")
     */
    protected $publicEndDt;

    /**
     * remark
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $remark;

    /**
     * showing_formats
     *
     * @var Collection<ShowingFormat>
     * @ORM\OneToMany(targetEntity="ShowingFormat", mappedBy="schedule", orphanRemoval=true, fetch="EAGER")
     */
    protected $showingFormats;

    /**
     * showing_theaters
     *
     * @var Collection<ShowingTheater>
     * @ORM\OneToMany(targetEntity="ShowingTheater", mappedBy="schedule", orphanRemoval=true, fetch="EAGER")
     */
    protected $showingTheaters;


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
     * @return Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * set title
     *
     * @param Title $title
     * @return void
     * @throws \LogicException
     */
    public function setTitle(Title $title)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get start_date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * set start_date
     *
     * @param \DateTime|string $startDate
     * @return void
     * @throws \LogicException
     */
    public function setStartDate($startDate)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get end_date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * set end_date
     *
     * @param \DateTime|string $endDate
     * @return void
     * @throws \LogicException
     */
    public function setEndDate($endDate)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get public_start_dt
     *
     * @return \DateTime
     */
    public function getPublicStartDt()
    {
        return $this->publicStartDt;
    }

    /**
     * set public_start_dt
     *
     * @param \DateTime|string $publicStartDt
     * @return void
     * @throws \LogicException
     */
    public function setPublicStartDt($publicStartDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get public_end_dt
     *
     * @return \DateTime
     */
    public function getPublicEndDt()
    {
        return $this->publicEndDt;
    }

    /**
     * set public_end_dt
     *
     * @param \DateTime|string $publicEndDt
     * @return void
     * @throws \LogicException
     */
    public function setPublicEndDt($publicEndDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get remark
     *
     * @return string|null
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * set remark
     *
     * @param string|null $remark
     * @return void
     * @throws \LogicException
     */
    public function setRemark(?string $remark)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get showing_formats
     *
     * @return Collection
     */
    public function getShowingFormats()
    {
        return $this->showingFormats;
    }

    /**
     * set showing_formats
     *
     * @param Collection $showingFormats
     * @return void
     * @throws \LogicException
     */
    public function setShowingFormats(Collection $showingFormats)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get showing_theaters
     *
     * @return Collection
     */
    public function getShowingTheaters()
    {
        return $this->showingTheaters;
    }

    /**
     * set showing_theaters
     *
     * @param Collection $showingTheaters
     * @return void
     * @throws \LogicException
     */
    public function setShowingTheaters(Collection $showingTheaters)
    {
        throw new \LogicException('Not allowed.');
    }
}
