<?php
/**
 * OyakoCinemaSchedule.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaSchedule entity class
 *
 * @ORM\Entity
 * @ORM\Table(name="oyako_cinema_schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaSchedule extends AbstractEntity
{
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
     * oyako_cinema_title
     *
     * @var OyakoCinemaTitle
     * @ORM\ManyToOne(targetEntity="OyakoCinemaTitle")
     * @ORM\JoinColumn(name="oyako_cinema_title_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $oyakoCinemaTitle;

    /**
     * date
     *
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * oyako_cinema_theaters
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="OyakoCinemaTheater", mappedBy="oyakoCinemaSchedule", orphanRemoval=true, fetch="EAGER")
     */
    protected $oyakoCinemaTheaters;

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
     * get oyako_cinema_title
     *
     * @return OyakoCinemaTitle
     */
    public function getOyakoCinemaTitle(): OyakoCinemaTitle
    {
        return $this->oyakoCinemaTitle;
    }

    /**
     * set oyako_cinema_title
     *
     * @param OyakoCinemaTitle $oyakoCinemaTitle
     * @return void
     * @throws \LogicException
     */
    public function setOyakoCinemaTitle(OyakoCinemaTitle $oyakoCinemaTitle)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * set date
     *
     * @param \DateTime|string $date
     * @return void
     * @throws \LogicException
     */
    public function setDate($date)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get oyako_cinema_theaters
     *
     * @return Collection
     */
    public function getOyakoCinemaTheaters(): Collection
    {
        return $this->oyakoCinemaTheaters;
    }
}
