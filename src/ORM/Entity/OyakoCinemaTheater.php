<?php
/**
 * OyakoCinemaTheater.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaTheater entity class
 *
 * @ORM\Entity
 * @ORM\Table(name="oyako_cinema_theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaTheater extends AbstractEntity
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
     * oyako_cinema_schedule
     *
     * @var OyakoCinemaSchedule
     * @ORM\ManyToOne(targetEntity="OyakoCinemaSchedule", inversedBy="oyakoCinemaTheaters")
     * @ORM\JoinColumn(name="oyako_cinema_schedule_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $oyakoCinemaSchedule;

    /**
     * theater
     *
     * @var Theater
     * @ORM\ManyToOne(targetEntity="Theater", fetch="EAGER")
     * @ORM\JoinColumn(name="theater_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $theater;

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
     * get oyako_cinema_schedule
     *
     * @return OyakoCinemaSchedule
     */
    public function getOyakoCinemaSchedule(): OyakoCinemaSchedule
    {
        return $this->oyakoCinemaSchedule;
    }

    /**
     * set oyako_cinema_schedule
     *
     * @param OyakoCinemaSchedule $oyakoCinemaSchedule
     * @return void
     * @throws \LogicException
     */
    public function setOyakoCinemaSchedule(OyakoCinemaSchedule $oyakoCinemaSchedule)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get theater
     *
     * @return Theater
     */
    public function getTheater(): Theater
    {
        return $this->theater;
    }

    /**
     * set theater
     *
     * @param Theater $theater
     * @return void
     * @throws \LogicException
     */
    public function setTheater(Theater $theater)
    {
        throw new \LogicException('Not allowed.');
    }
}